<?php
/**
 *  custom roles and capabilities for Event
 */

add_action('init', 'dynamic_dreamz_register_event_roles');

function dynamic_dreamz_register_event_roles() {

    // Capabilities
    $event_caps = [
        'read',
        'edit_event',
        'edit_events',
        'edit_others_events',
        'publish_events',
        'delete_event',
        'delete_events',
        'delete_others_events',
        'read_private_events',
    ];

    // role Event Submitter 
    if (!get_role('event_submitter')) {
        add_role('event_submitter', 'Event Submitter', []);
    }
    $submitter = get_role('event_submitter');
    if ($submitter) {
        foreach (['read', 'edit_event', 'edit_events'] as $cap) {
            $submitter->add_cap($cap);
        }
    }

    // role Event Moderator 
    if (!get_role('event_moderator')) {
        add_role('event_moderator', 'Event Moderator', []);
    }
    $moderator = get_role('event_moderator');
    if ($moderator) {
        foreach ($event_caps as $cap) {
            $moderator->add_cap($cap);
        }
    }

    //  role: Event Admin
    if (!get_role('event_admin')) {
        add_role('event_admin', 'Event Admin', []);
    }
    $admin = get_role('event_admin');
    if ($admin) {
        foreach ($event_caps as $cap) {
            $admin->add_cap($cap);
        }
        $admin->add_cap('manage_options');
    }
}
