<?php
// Check site is in maintenance mode
function dd_is_site_in_maintenance() {
    return get_option('maintenance_flag', 'false') === 'true';
}

// Show maintenance message
function dd_show_maintenance_message_or_form() {
    if ( dd_is_site_in_maintenance() ) {
        echo '<div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 my-4" role="alert">
                <p class="font-bold">Maintenance Mode</p>
                <p>The event submission form is currently disabled. Please try again later.</p>
              </div>';
    } else {
        dynamicdreamz_render_event_submission_form();
    }
}

// Block REST API form submission if maintenance is ON
add_filter('rest_pre_dispatch', function ($result, $server, $request) {
    $route = $request->get_route();

    if ( strpos($route, '/dd/v1/submit-event') !== false && dd_is_site_in_maintenance() ) {
        return new WP_REST_Response([
            'success' => false,
            'message' => 'The site is under maintenance. Event submissions are temporarily disabled.'
        ], 503);
    }

    return $result;
}, 10, 3);

// Admin notice when maintenance is ON
add_action('admin_notices', function () {
    if ( dd_is_site_in_maintenance() ) {
        echo '<div class="notice notice-warning"><p><strong>Maintenance Mode:</strong> Event form submissions are disabled.</p></div>';
    }
});

// Admin toggle for maintenance_flag setting on Settings > General
add_action('admin_init', function () {
    register_setting('general', 'maintenance_flag', [
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => 'false',
    ]);

    add_settings_field(
        'maintenance_flag',
        'Enable Maintenance Mode',
        function () {
            $value = get_option('maintenance_flag', 'false');
            echo '<label><input type="checkbox" name="maintenance_flag" value="true" ' . checked($value, 'true', false) . '> Check to enable</label>';
            echo '<p class="description">Disables the event form on frontend and API when enabled.</p>';
        },
        'general'
    );
});
