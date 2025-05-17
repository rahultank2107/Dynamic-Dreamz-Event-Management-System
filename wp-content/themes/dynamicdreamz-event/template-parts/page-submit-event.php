<?php
/* Template Name: Submit Event */
get_header();

if ( function_exists('dd_show_maintenance_message_or_form') ) {
    dd_show_maintenance_message_or_form();
} else {
    echo '<p>Form function missing.</p>';
}

get_footer();
