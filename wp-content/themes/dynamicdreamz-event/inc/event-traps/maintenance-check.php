<?php
/**
 * Easter Egg Trap Implementation
 */

// Add hidden field to event form
function add_easter_egg_field() {
    echo '<input type="hidden" name="easter_egg_note" id="easter_egg_note" value="' . esc_attr(wp_generate_password(12, false)) . '">';
}
add_action('event_form_start', 'add_easter_egg_field');

// Check for Easter Egg on submission
function check_easter_egg($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    
    if (!isset($_POST['easter_egg_note']) || empty($_POST['easter_egg_note'])) {
        update_post_meta($post_id, '_exploration_fail', true);
    } else {
        update_post_meta($post_id, '_ai_override', sanitize_text_field($_POST['easter_egg_note']));
    }
}
add_action('save_post_event', 'check_easter_egg', 10, 1);

/**
 * Maintenance Lockout System
 */

// Check maintenance mode on form display
function check_maintenance_mode() {
    if (get_option('maintenance_flag')) {
        wp_die(
            '<div class="p-4 max-w-md mx-auto mt-8 bg-red-100 border-l-4 border-red-500 text-red-700">' .
            '<p class="font-bold">Maintenance Mode</p>' .
            '<p>Event submissions are temporarily disabled for maintenance. Please try again later.</p>' .
            '</div>',
            'Maintenance Mode',
            ['response' => 503]
        );
    }
}
add_action('event_form_before', 'check_maintenance_mode');

// Block REST API in maintenance mode
function block_rest_in_maintenance($result, $server, $request) {
    if (strpos($request->get_route(), 'events') !== false && get_option('maintenance_flag')) {
        return new WP_Error(
            'maintenance_mode',
            'Event system is currently under maintenance',
            ['status' => 503]
        );
    }
    return $result;
}
add_filter('rest_pre_dispatch', 'block_rest_in_maintenance', 10, 3);

// Add maintenance toggle to admin bar
function add_maintenance_toggle($wp_admin_bar) {
    if (!current_user_can('manage_options')) return;
    
    $wp_admin_bar->add_node([
        'id'    => 'maintenance_toggle',
        'title' => get_option('maintenance_flag') ? '🚧 Disable Maintenance' : '⚙️ Enable Maintenance',
        'href'  => '#',
        'meta'  => ['onclick' => 'toggleMaintenanceMode(); return false;']
    ]);
}
add_action('admin_bar_menu', 'add_maintenance_toggle', 100);

// AJAX handler for maintenance toggle
function handle_maintenance_toggle() {
    check_ajax_referer('maintenance_toggle');
    update_option('maintenance_flag', !get_option('maintenance_flag'));
    wp_die();
}
add_action('wp_ajax_toggle_maintenance', 'handle_maintenance_toggle');