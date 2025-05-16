<?php
// add_action('init', function () {
//     register_post_status('pending_review', [
//         'label' => 'Pending Review',
//         'public' => true,
//         'label_count' => _n_noop('Pending Review (%s)', 'Pending Review (%s)'),
//         'post_type' => ['event'],
//     ]);

//     register_post_status('scheduled', [
//         'label' => 'Scheduled',
//         'public' => true,
//         'label_count' => _n_noop('Scheduled (%s)', 'Scheduled (%s)'),
//         'post_type' => ['event'],
//     ]);

//     register_post_status('rejected', [
//         'label' => 'Rejected',
//         'public' => true,
//         'label_count' => _n_noop('Rejected (%s)', 'Rejected (%s)'),
//         'post_type' => ['event'],
//     ]);
// });

// add_filter('display_post_states', function ($states, $post) {
//     if ($post->post_type === 'event') {
//         $status = $post->post_status;
//         $custom = ['pending_review', 'scheduled', 'rejected'];
//         if (in_array($status, $custom)) {
//             $states[] = ucfirst(str_replace('_', ' ', $status));
//         }
//     }
//     return $states;
// }, 10, 2);
