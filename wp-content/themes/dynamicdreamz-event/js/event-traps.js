// Maintenance mode toggle handler
function toggleMaintenanceMode() {
    fetch(ajaxurl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=toggle_maintenance&nonce=' + document.getElementById('maintenance-nonce').value
    }).then(() => window.location.reload());
}

// Frontend form validation
document.addEventListener('DOMContentLoaded', function () {
    const eventForm = document.getElementById('event-submit-form');
    if (eventForm) {
        eventForm.addEventListener('submit', function (e) {
            if (!document.getElementById('easter_egg_note') ||
                document.getElementById('easter_egg_note').value === '') {
                alert('Form validation failed - required field missing');
                e.preventDefault();
            }

            if (document.querySelector('.maintenance-notice')) {
                alert('System is in maintenance mode');
                e.preventDefault();
            }
        });
    }
});