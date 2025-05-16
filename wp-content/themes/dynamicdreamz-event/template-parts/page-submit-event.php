<?php
/* Template Name: Submit Event */
get_header();
?>

<div class="event-submission-form">
    <?php dynamicdreamz_render_event_submission_form(); ?>
</div>


<?php if (is_active_sidebar('event_sidebar')) : ?>
    <aside id="sidebar">
        <?php dynamic_sidebar('event_sidebar'); ?>
    </aside>
<?php endif; ?>
