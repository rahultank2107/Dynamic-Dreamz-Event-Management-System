<?php
// Add popularity meta box
add_action('add_meta_boxes', function () {
    add_meta_box(
        'event_popularity_meta_box',
        'Event Popularity',
        'render_event_popularity_meta_box',
        'event',
        'side',
        'default'
    );
});

function render_event_popularity_meta_box($post) {
    $value = get_post_meta($post->ID, '_event_popularity', true);
    wp_nonce_field('save_event_popularity', 'event_popularity_nonce');
    ?>
    <label for="event_popularity_field">Popularity Score:</label>
    <input type="number" name="event_popularity" id="event_popularity_field" value="<?php echo esc_attr($value); ?>" min="0" style="width:100%;" />
    <?php
}

// Save the popularity
add_action('save_post_event', function ($post_id) {
    if (!isset($_POST['event_popularity_nonce']) || !wp_verify_nonce($_POST['event_popularity_nonce'], 'save_event_popularity')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['event_popularity'])) {
        update_post_meta($post_id, '_event_popularity', intval($_POST['event_popularity']));
    }
});

function track_event_views($post_id) {
    if (get_post_type($post_id) !== 'event') return;

    $views = (int) get_post_meta($post_id, '_event_popularity', true);
    $views++;
    update_post_meta($post_id, '_event_popularity', $views);
}

add_action('wp', function () {
    if (is_singular('event') && !is_admin()) {
        global $post;
        track_event_views($post->ID);
    }
});

// Sortable in admin list
add_filter('manage_edit-event_sortable_columns', function ($columns) {
    $columns['event_popularity'] = 'event_popularity';
    return $columns;
});

add_filter('manage_event_posts_columns', function ($columns) {
    $columns['event_popularity'] = 'Popularity';
    return $columns;
});

add_action('manage_event_posts_custom_column', function ($column, $post_id) {
    if ($column === 'event_popularity') {
        echo (int) get_post_meta($post_id, '_event_popularity', true);
    }
}, 10, 2);

add_action('pre_get_posts', function ($query) {
    if (!is_admin() || !$query->is_main_query()) return;
    if ($query->get('orderby') === 'event_popularity') {
        $query->set('meta_key', '_event_popularity');
        $query->set('orderby', 'meta_value_num');
    }
});
