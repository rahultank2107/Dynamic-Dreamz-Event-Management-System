<?php
// Add filter dropdowns
add_action('restrict_manage_posts', function () {
    global $typenow;
    if ($typenow !== 'event') return;
    $taxonomy = 'city';
    $selected = $_GET[$taxonomy] ?? '';
    $dropdown_args = [
        'show_option_all' => 'All Cities',
        'taxonomy'        => $taxonomy,
        'name'            => $taxonomy,
        'orderby'         => 'name',
        'selected'        => $selected,
        'hierarchical'    => true,
        'depth'           => 3,
        'show_count'      => false,
        'hide_empty'      => false,
        'value_field'     => 'slug',
    ];
    wp_dropdown_categories($dropdown_args);

    $date_range = $_GET['event_date_range'] ?? '';
    ?>
    <select name="event_date_range">
        <option value="">Event Dates</option>
        <option value="past" <?php selected($date_range, 'past'); ?>>Past</option>
        <option value="future" <?php selected($date_range, 'future'); ?>>Future</option>
    </select>
    <?php
}, 10);

// Modify  on selected filters
add_action('pre_get_posts', function ($query) {
    if (!is_admin() || !$query->is_main_query()) return;

    $post_type = $query->get('post_type');
    if ($post_type !== 'event') return;

    if (!empty($_GET['event_date_range'])) {
        $meta_query = $query->get('meta_query') ?: [];
        $today = date('Y-m-d');
        $meta_query[] = [
            'key'     => 'event_start',
            'value'   => $today,
            'compare' => $_GET['event_date_range'] === 'past' ? '<' : '>=',
            'type'    => 'DATE',
        ];
        $query->set('meta_query', $meta_query);
    }

    if (!empty($_GET['city'])) {
        $query->set('tax_query', [
            [
                'taxonomy' => 'city',
                'field'    => 'slug',
                'terms'    => sanitize_text_field($_GET['city']),
            ],
        ]);
    }
});
