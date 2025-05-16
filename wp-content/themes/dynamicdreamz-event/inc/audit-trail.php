<?php
add_action('init', 'dynamic_dreamz_create_event_logs_table_once');
function dynamic_dreamz_create_event_logs_table_once() {
    if (!is_admin()) return;
    if (get_option('dynamic_dreamz_event_logs_installed') === 'yes') return;

    global $wpdb;
    $table = $wpdb->prefix . 'event_logs';
    $charset = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        event_id BIGINT UNSIGNED NOT NULL,
        user_id BIGINT UNSIGNED NOT NULL,
        action VARCHAR(50),
        changes LONGTEXT,
        timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);

    update_option('dynamic_dreamz_event_logs_installed', 'yes');
}


add_action('post_updated', function ($post_ID, $post_after, $post_before) {
    // Only log updates for 'event' post type
    if ($post_after->post_type !== 'event') {
        return;
    }

    global $wpdb;
    $table = $wpdb->prefix . 'event_logs';
    $user_id = get_current_user_id();

    $changes = [];

    // Compare title and content for changes
    if ($post_before->post_title !== $post_after->post_title) {
        $changes['title'] = [
            'old' => $post_before->post_title,
            'new' => $post_after->post_title,
        ];
    }

    if ($post_before->post_content !== $post_after->post_content) {
        $changes['content'] = [
            'old' => $post_before->post_content,
            'new' => $post_after->post_content,
        ];
    }

    // If no changes, do not log
    if (empty($changes)) {
        return;
    }

    // Insert the audit log entry
    $wpdb->insert(
        $table,
        [
            'event_id'  => $post_ID,
            'user_id'   => $user_id,
            'action'    => 'updated',
            'changes'   => wp_json_encode($changes),
            'timestamp' => current_time('mysql'),
        ]
    );

}, 10, 3);
