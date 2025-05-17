<?php
add_action('save_post_event', 'generate_qr_code_on_publish', 20, 3);

function generate_qr_code_on_publish($post_id, $post, $update) {
    if ($post->post_status !== 'publish') return;

    if (did_action('generate_qr_code_on_publish_' . $post_id)) return;
    do_action('generate_qr_code_on_publish_' . $post_id);

    if (get_post_meta($post_id, '_event_qr_code_id', true)) return;

    $event_url = get_permalink($post_id);
    $qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($event_url);

    $tmp = download_url($qr_url);
    if (is_wp_error($tmp)) return;

    $file_array = [
        'name'     => 'event-' . $post_id . '-qr.png',
        'tmp_name' => $tmp
    ];

    // Include necessary files
    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $attachment_id = media_handle_sideload($file_array, $post_id);

    if (!is_wp_error($attachment_id)) {
        update_post_meta($post_id, '_event_qr_code_id', $attachment_id);
    } else {
        @unlink($file_array['tmp_name']);
    }
}


add_action('add_meta_boxes', function () {
    add_meta_box('event_qr_code', 'Event QR Code', 'render_event_qr_code_meta_box', 'event', 'side');
});

function render_event_qr_code_meta_box($post) {
    $attachment_id = get_post_meta($post->ID, '_event_qr_code_id', true);
    if ($attachment_id) {
        $qr_url = wp_get_attachment_url($attachment_id);
        echo '<img src="' . esc_url($qr_url) . '" style="max-width:100%;" alt="QR Code">';
    } else {
        echo '<p>QR Code will be generated when this event is published.</p>';
    }
}
