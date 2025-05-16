document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('dd-event-form');
    const eventType = document.getElementById('event_type');
    const venueFields = document.getElementById('venue_fields');
    const message = document.getElementById('form-message');
    const submitButton = form.querySelector('button[type="submit"]');


    if (!form.classList.contains('listener-attached')) {
        form.classList.add('listener-attached');

        eventType.addEventListener('change', function () {
            venueFields.style.display = this.value === 'venue' ? 'block' : 'none';
        });

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            submitButton.disabled = true;
            message.textContent = 'Submitting...';

            const formData = new FormData(form);

            // Phone Validation
            const phone = formData.get('organizer_phone');
            const phonePattern = /^\+91\d{10}$/;
            if (!phonePattern.test(phone)) {
                message.textContent = 'Invalid phone format. Use +91-XXX-XXX-XXXX';
                submitButton.disabled = false;
                return;
            }

            // Date Validation
            const startDate = new Date(formData.get('event_start'));
            const now = new Date();
            if (startDate < now || startDate.getDay() === 0 || startDate.getDay() === 6) {
                message.textContent = 'Event must start on a weekday and in the future.';
                submitButton.disabled = false;
                return;
            }

            // Honeypot check
            if (formData.get('event_bot_field')) {
                message.textContent = 'Bot detected.';
                submitButton.disabled = false;
                return;
            }

            // Image file Validation
            const imageFile = formData.get('event_image');
            if (imageFile && imageFile.size > 0) {
                const allowedTypes = ['image/png', 'image/jpeg'];
                const maxSize = 2 * 1024 * 1024;

                if (!allowedTypes.includes(imageFile.type)) {
                    message.textContent = 'Only PNG and JPEG images are allowed.';
                    submitButton.disabled = false;
                    return;
                }

                if (imageFile.size > maxSize) {
                    message.textContent = 'Image must be less than 2MB.';
                    submitButton.disabled = false;
                    return;
                }
            }

            const submitData = () => {
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
                        console.log('API response:', data);
                        message.textContent = data.success ? 'Event submitted!' : data.message;
                    })
                    .catch(() => {
                        message.textContent = 'Retrying...';

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
                                message.textContent = data.success ? 'Event submitted!' : data.message;
                            })
                            .catch(() => {
                                message.textContent = 'Failed to submit.';
                            });
                    })
                    .finally(() => {
                        submitButton.disabled = false;
                    });
            };

            submitData();
        });
    }
});