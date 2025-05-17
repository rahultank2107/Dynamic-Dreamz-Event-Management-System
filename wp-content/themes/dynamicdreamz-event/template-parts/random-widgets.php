<?php
/**
 * Template Name: Random Event Page
 */

get_header();
?>

<div class="container">
    <h1><?php the_title(); ?></h1>

    <div class="random-event-widget">
        <?php the_widget('Random_Event_Widget'); ?>
    </div>
</div>
<?php get_footer(); ?>
