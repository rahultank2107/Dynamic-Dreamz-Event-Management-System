<?php
register_activation_hook(__FILE__, 'register_custom_event_roles');
function register_custom_event_roles() {
    add_role('event_submitter', 'Event Submitter', [
        'read' => true,
        'edit_events' => true,
        'upload_files' => true,
    ]);

    add_role('event_moderator', 'Event Moderator', [
        'read' => true,
        'edit_others_events' => true,
        'publish_events' => true,
        'edit_events' => true,
    ]);

    add_role('event_admin', 'Event Admin', [
        'read' => true,
        'edit_events' => true,
        'edit_others_events' => true,
        'delete_events' => true,
        'publish_events' => true,
        'manage_options' => true,
    ]);
}
