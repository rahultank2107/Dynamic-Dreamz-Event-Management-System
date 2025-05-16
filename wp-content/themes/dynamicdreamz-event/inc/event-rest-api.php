<?php
add_action('rest_api_init', function () {
    register_rest_route('dynamicdreamz/v1', '/submit-event', [
        'methods'  => 'POST',
        'callback' => 'ddz_handle_event_submission',
        'permission_callback' => '__return_true',
    ]);
});

add_action('rest_api_init', function () {
    register_rest_route('v1', '/events', [
        'methods' => 'GET',
        'callback' => 'get_filtered_events',
        'permission_callback' => '__return_true',
    ]);
});

function ddz_handle_event_submission($request) {
    // WordPress functions Loaded 
    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $params = $request->get_params();

    if (!empty($params['event_bot_field'])) {
        return new WP_REST_Response(['success' => false, 'message' => 'Spam detected.'], 403);
    }

    $title = sanitize_text_field($params['event_title']);
    $start = sanitize_text_field($params['event_start']);
    $end = sanitize_text_field($params['event_end']);

    if (empty($title) || empty($start) || empty($end)) {
        return new WP_REST_Response(['success' => false, 'message' => 'Required fields are missing.'], 400);
    }

    $post_id = wp_insert_post([
        'post_type'   => 'event',
        'post_title'  => $title,
        'post_status' => 'pending',
    ]);

    if (is_wp_error($post_id)) {
        return new WP_REST_Response(['success' => false, 'message' => 'Failed to create event.'], 500);
    }

    // Save meta events details
    update_post_meta($post_id, 'event_start', $start);
    update_post_meta($post_id, 'event_end', $end);
    update_post_meta($post_id, 'organizer_name', sanitize_text_field($params['organizer_name']));
    update_post_meta($post_id, 'organizer_email', sanitize_email($params['organizer_email']));
    update_post_meta($post_id, 'organizer_phone', sanitize_text_field($params['organizer_phone']));
    update_post_meta($post_id, 'event_type', sanitize_text_field($params['event_type']));
    update_post_meta($post_id, 'venue', sanitize_text_field($params['venue']));
    update_post_meta($post_id, 'venue_coords', sanitize_text_field($params['venue_coords']));
    update_post_meta($post_id, 'ticket_price', floatval($params['ticket_price']));

    if (!empty($params['event_city'])) {
        wp_set_object_terms($post_id, sanitize_text_field($params['event_city']), 'city');
    }

    // image upload detilas
    if (!empty($_FILES['event_image'])) {
        $image_id = media_handle_upload('event_image', $post_id);
        if (!is_wp_error($image_id)) {
            set_post_thumbnail($post_id, $image_id);
        }
    }

    return new WP_REST_Response(['success' => true, 'message' => 'Event submitted successfully.'], 200);
}

function get_filtered_events($request) {
    $args = [
        'post_type' => 'event',
        'posts_per_page' => 10,
        'post_status' => 'publish',
        'meta_query' => [
            [
                'key' => 'event_start',
                'value' => date('Y-m-d'),
                'compare' => '>=',
                'type' => 'DATE'
            ]
        ],
        'tax_query' => [],
    ];

    if ($city = $request->get_param('city')) {
        $args['tax_query'][] = [
            'taxonomy' => 'city',
            'field' => 'slug',
            'terms' => $city,
        ];
    }

    if ($type = $request->get_param('type')) {
        $args['tax_query'][] = [
            'taxonomy' => 'event_type',
            'field' => 'slug',
            'terms' => $type,
        ];
    }

    $query = new WP_Query($args);
    $events = [];

    foreach ($query->posts as $post) {
        $events[] = [
            'id' => $post->ID,
            'title' => get_the_title($post),
            'start_date' => get_post_meta($post->ID, 'event_start', true),
            'url' => get_permalink($post),
        ];
    }

    return rest_ensure_response($events);
}