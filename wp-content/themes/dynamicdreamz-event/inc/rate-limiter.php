<?php
add_filter('rest_pre_dispatch', function ($result, $server, $request) {
    if (strpos($request->get_route(), '/v1/events') === false) return $result;

    $ip = $_SERVER['REMOTE_ADDR'];
    $key = 'rate_limit_' . md5($ip);
    $requests = get_transient($key) ?: 0;

    if ($requests >= 10) {
        return new WP_REST_Response(['message' => 'Rate limit exceeded'], 429);
    }

    set_transient($key, $requests + 1, MINUTE_IN_SECONDS);
    return $result;
}, 10, 3);
