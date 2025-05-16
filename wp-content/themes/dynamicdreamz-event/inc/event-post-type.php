<?php
/**
 * Register Event Post Type
 */

function dynamic_dreamz_register_event_post_type() {
    $labels = array(
        'name'                  => _x('Events', 'Post Type General Name', 'dynamic-dreamz'),
        'singular_name'         => _x('Event', 'Post Type Singular Name', 'dynamic-dreamz'),
        'menu_name'             => __('Events', 'dynamic-dreamz'),
        'all_items'             => __('All Events', 'dynamic-dreamz'),
        'view_item'             => __('View Event', 'dynamic-dreamz'),
        'add_new_item'          => __('Add New Event', 'dynamic-dreamz'),
        'add_new'               => __('Add New', 'dynamic-dreamz'),
        'edit_item'             => __('Edit Event', 'dynamic-dreamz'),
        'update_item'           => __('Update Event', 'dynamic-dreamz'),
        'search_items'          => __('Search Events', 'dynamic-dreamz'),
        'not_found'             => __('Not Found', 'dynamic-dreamz'),
        'not_found_in_trash'    => __('Not Found in Trash', 'dynamic-dreamz'),
    );

    $args = array(
        'label'                 => __('Event', 'dynamic-dreamz'),
        'description'           => __('Event post type', 'dynamic-dreamz'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'page-attributes'),
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => true,
        'show_in_admin_bar'     => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-calendar-alt',
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'rewrite'               => array('slug' => 'events', 'with_front' => false),
    );

    register_post_type('event', $args);
}
add_action('init', 'dynamic_dreamz_register_event_post_type', 0);


/**
 * Register Event Post Type
 */

// function dynamic_dreamz_register_event_post_type() {
//     $labels = array(
//         'name'                  => _x('Events', 'Post Type General Name', 'dynamic-dreamz'),
//         'singular_name'         => _x('Event', 'Post Type Singular Name', 'dynamic-dreamz'),
//         'menu_name'             => __('Events', 'dynamic-dreamz'),
//         'all_items'             => __('All Events', 'dynamic-dreamz'),
//         'view_item'             => __('View Event', 'dynamic-dreamz'),
//         'add_new_item'          => __('Add New Event', 'dynamic-dreamz'),
//         'add_new'               => __('Add New', 'dynamic-dreamz'),
//         'edit_item'             => __('Edit Event', 'dynamic-dreamz'),
//         'update_item'           => __('Update Event', 'dynamic-dreamz'),
//         'search_items'          => __('Search Events', 'dynamic-dreamz'),
//         'not_found'             => __('Not Found', 'dynamic-dreamz'),
//         'not_found_in_trash'    => __('Not Found in Trash', 'dynamic-dreamz'),
//     );

//     $args = array(
//         'label'                 => __('Event', 'dynamic-dreamz'),
//         'description'           => __('Event post type', 'dynamic-dreamz'),
//         'labels'                => $labels,
//         'supports'              => array('title', 'editor', 'thumbnail', 'page-attributes'),
//         'hierarchical'          => true,
//         'public'                => true,
//         'show_ui'               => true,
//         'show_in_menu'          => true,
//         'show_in_nav_menus'     => true,
//         'show_in_admin_bar'     => true,
//         'menu_position'         => 5,
//         'menu_icon'             => 'dashicons-calendar-alt',
//         'can_export'            => true,
//         'has_archive'           => true,
//         'exclude_from_search'   => false,
//         'publicly_queryable'    => true,
//         'capability_type'       => ['event', 'events'], // ✅ Corrected here
//         'map_meta_cap'          => true,                // ✅ Enables capability mapping
//         'rewrite'               => array('slug' => 'events', 'with_front' => false),
//     );

//     register_post_type('event', $args);
// }
// add_action('init', 'dynamic_dreamz_register_event_post_type', 0);
