<?php

add_filter('wp_unique_post_slug', function ($slug, $post_ID, $post_status, $post_type, $post_parent) {
    if ($post_type !== 'event') return $slug;

    // Get post date (fallback to current time if not set)
    $post_date = get_post_field('post_date', $post_ID);
    if (empty($post_date) || $post_date === '0000-00-00 00:00:00') {
        $post_date = current_time('mysql');
    }
    $year = date('Y', strtotime($post_date));

    global $wpdb;
    $existing = $wpdb->get_var($wpdb->prepare("
        SELECT ID FROM {$wpdb->posts}
        WHERE post_name = %s
        AND post_type = %s
        AND ID != %d
        AND YEAR(post_date) = %d
        LIMIT 1
    ", $slug, 'event', $post_ID, $year));

    if ($existing) {
        // Generate suffix only if conflict in same year
        $slug .= '-' . strtolower(wp_generate_password(4, false, false));
    }

    return $slug;
}, 10, 5);
