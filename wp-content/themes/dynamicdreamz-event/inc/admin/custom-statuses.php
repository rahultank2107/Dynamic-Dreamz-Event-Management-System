<?php
// Register custom post statuses
add_action('init', function () {
    $statuses = [
        'scheduled'      => 'Scheduled',
        'rejected'       => 'Rejected',
    ];

    foreach ($statuses as $key => $label) {
        register_post_status($key, [
            'label'                     => _x($label, 'post'),
            'public'                    => true,
            'internal'                  => false,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop("$label <span class='count'>(%s)</span>", "$label <span class='count'>(%s)</span>"),
        ]);
    }
});

// Show custom status
add_filter('display_post_states', function ($states, $post) {
    if ($post->post_type === 'event') {
        $custom_states = [
            'scheduled'      => __('Scheduled'),
            'rejected'       => __('Rejected'),
        ];
        if (isset($custom_states[$post->post_status])) {
            $states[] = $custom_states[$post->post_status];
        }
    }
    return $states;
}, 10, 2);

// Add custom statuses 
add_filter('views_edit-event', function ($views) {
    $statuses = ['scheduled', 'rejected'];
    
    foreach ($statuses as $status) {
        $count = wp_count_posts('event')->$status;
        if ($count > 0) {
            $views[$status] = sprintf(
                '<a href="%s"%s>%s <span class="count">(%d)</span></a>',
                add_query_arg(['post_status' => $status, 'post_type' => 'event'], 'edit.php'),
                (isset($_REQUEST['post_status']) && $_REQUEST['post_status'] === $status ? ' class="current"' : ''),
                _x(ucfirst($status), 'post'),
                $count
            );
        }
    }
    
    return $views;
});

// Add custom statuses quick edit
function add_custom_status_to_dropdown() {
    global $post;
    if ($post && $post->post_type !== 'event') return;

    $selected = $post ? esc_js($post->post_status) : '';
    ?>
    <script>
        jQuery(document).ready(function($) {
            var $select = $('select#post_status');
            var customStatuses = {
                'scheduled': 'Scheduled',
                'rejected': 'Rejected'
            };

            $.each(customStatuses, function(value, label) {
                if ($select.find('option[value="' + value + '"]').length === 0) {
                    var isSelected = value === '<?php echo $selected; ?>' ? ' selected="selected"' : '';
                    $select.append('<option value="' + value + '"' + isSelected + '>' + label + '</option>');
                }
            });

            var $quickEditSelect = $('.inline-edit-status select');
            $.each(customStatuses, function(value, label) {
                if ($quickEditSelect.find('option[value="' + value + '"]').length === 0) {
                    $quickEditSelect.append('<option value="' + value + '">' + label + '</option>');
                }
            });
        });
    </script>
    <?php
}
add_action('admin_footer-post.php', 'add_custom_status_to_dropdown');
add_action('admin_footer-post-new.php', 'add_custom_status_to_dropdown');
add_action('admin_footer-edit.php', 'add_custom_status_to_dropdown');