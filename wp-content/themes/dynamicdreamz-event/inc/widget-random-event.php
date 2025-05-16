<?php
add_action('widgets_init', function () {
    register_widget('Random_Event_Widget');
});

class Random_Event_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct('random_event_widget', 'Random Event Widget');
    }

    public function widget($args, $instance) {
        $cached = get_transient('random_event_widget');
        if (!$cached) {
            $query = new WP_Query([
                'post_type' => 'event',
                'posts_per_page' => 1,
                'orderby' => 'rand',
                'meta_query' => [[
                    'key' => 'event_start',
                    'value' => date('Y-m-d'),
                    'compare' => '>=',
                    'type' => 'DATE'
                ]]
            ]);

            if ($query->have_posts()) {
                ob_start();
                while ($query->have_posts()) {
                    $query->the_post();
                    echo '<div><h4>' . get_the_title() . '</h4>';
                    echo '<p>' . get_the_excerpt() . '</p>';
                    echo '<a href="' . esc_url(get_permalink()) . '?_wpnonce=' . wp_create_nonce('view_event') . '" class="button">View Event</a></div>';
                }
                $cached = ob_get_clean();
                set_transient('random_event_widget', $cached, 10 * MINUTE_IN_SECONDS);
            }
            wp_reset_postdata();
        }
        echo $cached;
    }
}
