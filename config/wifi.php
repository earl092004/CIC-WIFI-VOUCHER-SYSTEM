<?php

return [
    'student_daily_limit' => min(4, max(1, (int) env('WIFI_STUDENT_DAILY_LIMIT', 1))),
    'voucher_duration_minutes' => (int) env('WIFI_VOUCHER_DURATION_MINUTES', 60),
    'networks' => [
        'student' => env('WIFI_STUDENT_NETWORK', 'CIC-Student'),
        'visitor' => env('WIFI_VISITOR_NETWORK', 'CIC-Visitors'),
    ],
];
