<?php
/**
 * Register City Taxonomy Cities
 * @package Dynamic-Dreamz
 * @author Rahul
*/
function dynamic_dreamz_register_city_taxonomy() {
    $labels = array(
        'name'                       => _x('Cities', 'Taxonomy General Name', 'dynamic-dreamz'),
        'singular_name'              => _x('City', 'Taxonomy Singular Name', 'dynamic-dreamz'),
        'menu_name'                  => __('Cities', 'dynamic-dreamz'),
        'all_items'                  => __('All Cities', 'dynamic-dreamz'),
        'parent_item'                => __('Parent Location', 'dynamic-dreamz'),
        'parent_item_colon'          => __('Parent Location:', 'dynamic-dreamz'),
        'new_item_name'              => __('New Location Name', 'dynamic-dreamz'),
        'add_new_item'               => __('Add New Location', 'dynamic-dreamz'),
        'edit_item'                  => __('Edit Location', 'dynamic-dreamz'),
        'update_item'                => __('Update Location', 'dynamic-dreamz'),
        'view_item'                  => __('View Location', 'dynamic-dreamz'),
        'separate_items_with_commas' => __('Separate locations with commas', 'dynamic-dreamz'),
        'add_or_remove_items'       => __('Add or remove locations', 'dynamic-dreamz'),
        'choose_from_most_used'      => __('Choose from the most used', 'dynamic-dreamz'),
        'popular_items'              => __('Popular Locations', 'dynamic-dreamz'),
        'search_items'              => __('Search Locations', 'dynamic-dreamz'),
        'not_found'                 => __('Not Found', 'dynamic-dreamz'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'             => true,
        'rewrite'                    => array(
            'slug' => 'event-location',
            'hierarchical' => true,
            'with_front' => false
        ),
    );

    register_taxonomy('city', array('event'), $args);
}
add_action('init', 'dynamic_dreamz_register_city_taxonomy', 0);