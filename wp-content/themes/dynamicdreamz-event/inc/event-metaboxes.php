<?php
/**
 * Event Custom Meta Boxes
 * @package Dynamic-Dreamz
 * @author Rahul
 */

add_action('add_meta_boxes', 'dd_add_event_metaboxes');
function dd_add_event_metaboxes() {
    add_meta_box(
        'event_details_metabox',
        'Event Details',
        'dd_render_event_details_fields',
        'event',
        'normal',
        'default'
    );
}


function dd_render_event_details_fields($post) {
    wp_nonce_field('dd_save_event_details', 'dd_event_nonce');

    $values = get_post_meta($post->ID);

    $fields = [
        'event_start'       => 'Start Date/Time',
        'event_end'         => 'End Date/Time',
        'organizer_name'    => 'Organizer Name',
        'organizer_email'   => 'Organizer Email',
        'organizer_phone'   => 'Organizer Phone',
        'venue'             => 'Venue',
        'venue_coords'      => 'Map Coordinates',
        'ticket_price'      => 'Ticket Price',
    ];

    ?>
    <div class="main-dd-cls">
        <?php
        
    foreach ($fields as $field => $label) {
        $value = esc_attr($values[$field][0] ?? '');
        $type = ($field === 'ticket_price') ? 'number' : (str_contains($field, 'email') ? 'email' : 'text');
        if (in_array($field, ['event_start', 'event_end'])) {
            $type = 'datetime-local';
        }
        echo "<div class='dd-meta-field'>
                <label for='{$field}'>{$label}</label>
                <input type='{$type}' id='{$field}' name='{$field}' value='{$value}'>
            </div>";

    }?></div><?php
}

add_action('save_post_event', 'dd_save_event_details');
function dd_save_event_details($post_id) {
    if (!isset($_POST['dd_event_nonce']) || !wp_verify_nonce($_POST['dd_event_nonce'], 'dd_save_event_details')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = [
        'event_start',
        'event_end',
        'organizer_name',
        'organizer_email',
        'organizer_phone',
        'venue',
        'venue_coords',
        'ticket_price',
    ];

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}
