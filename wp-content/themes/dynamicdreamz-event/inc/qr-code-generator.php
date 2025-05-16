<?php
add_action('publish_event', function ($post_ID) {
    $event_url = get_permalink($post_ID);
    $qr_api = 'https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=' . urlencode($event_url);

    // Use cURL to fetch QR code image
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $qr_api);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $image_data = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200 || !$image_data) {
        error_log("QR code fetch failed for event ID {$post_ID} with HTTP status $http_code");
        return;
    }

    $upload_dir = wp_upload_dir();
    $filename = "event_qr_{$post_ID}.png";
    $file_path = $upload_dir['path'] . '/' . $filename;

    file_put_contents($file_path, $image_data);

    $attachment = [
        'guid'           => $upload_dir['url'] . '/' . $filename,
        'post_mime_type' => 'image/png',
        'post_title'     => "QR for Event {$post_ID}",
        'post_content'   => '',
        'post_status'    => 'inherit'
    ];

    require_once ABSPATH . 'wp-admin/includes/image.php';
    $attach_id = wp_insert_attachment($attachment, $file_path);
    $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
    wp_update_attachment_metadata($attach_id, $attach_data);

    update_post_meta($post_ID, 'event_qr_code_id', $attach_id);
});
