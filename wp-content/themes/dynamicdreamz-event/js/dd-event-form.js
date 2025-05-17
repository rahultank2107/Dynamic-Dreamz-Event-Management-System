document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('dd-event-form');
    const eventType = document.getElementById('event_type');
    const venueFields = document.getElementById('venue_fields');
    const submitButton = form.querySelector('button[type="submit"]');
    const phoneInput = document.getElementById('organizer_phone');

    if (!form.classList.contains('listener-attached')) {
        form.classList.add('listener-attached');

        eventType.addEventListener('change', function () {
            venueFields.style.display = this.value === 'venue' ? 'block' : 'none';
        });

        phoneInput.addEventListener('input', function () {
            let val = this.value;

            if (!val.startsWith('+91 ')) {
                val = '+91 ';
            }

            let afterPrefix = val.slice(4);

            afterPrefix = afterPrefix.replace(/\D/g, '');

            if (afterPrefix.length > 10) {
                afterPrefix = afterPrefix.slice(0, 10);
            }

            this.value = '+91 ' + afterPrefix;
        });

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            submitButton.disabled = true;

            const formData = new FormData(form);

            // Phone Validation
            let phone = formData.get('organizer_phone').trim();

            // Remove the space after +91 to match pattern +911234567890
            phone = phone.replace('+91 ', '+91');

            const phonePattern = /^\+91\d{10}$/;
            if (!phonePattern.test(phone)) {
                alert('Invalid phone format. Use +91 XXXXXXXXXX');
                submitButton.disabled = false;
                return;
            }

            // Update formData with sanitized phone
            formData.set('organizer_phone', phone);

            // Date Validation
            const startDate = new Date(formData.get('event_start'));
            const now = new Date();
            if (startDate < now || startDate.getDay() === 0 || startDate.getDay() === 6) {
                alert('Event must start on a weekday and in the future.');
                submitButton.disabled = false;
                return;
            }

            // Honeypot check
            if (formData.get('event_bot_field')) {
                alert('Bot detected.');
                submitButton.disabled = false;
                return;
            }

            // Image file Validation
            const imageFile = formData.get('event_image');
            if (imageFile && imageFile.size > 0) {
                const allowedTypes = ['image/png', 'image/jpeg'];
                const maxSize = 2 * 1024 * 1024;

                if (!allowedTypes.includes(imageFile.type)) {
                    alert('Only PNG and JPEG images are allowed.');
                    submitButton.disabled = false;
                    return;
                }

                if (imageFile.size > maxSize) {
                    alert('Image must be less than 2MB.');
                    submitButton.disabled = false;
                    return;
                }
            }

            const submitData = (retry = false) => {
                fetch(dd_vars.rest_url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-WP-Nonce': dd_vars.nonce,
                    },
                    credentials: 'same-origin'
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert('Event submitted successfully!');
                            window.location.reload();
                        } else {
                            alert(data.message || 'Something went wrong.');
                            submitButton.disabled = false;
                        }
                    })
                    .catch(() => {
                        if (!retry) {
                            alert('Retrying submission...');
                            submitData(true);
                        } else {
                            alert('Failed to submit. Please try again.');
                            submitButton.disabled = false;
                        }
                    });
            };

            submitData();
        });
    }
});
