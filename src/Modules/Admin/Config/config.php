<?php

return [
    'name' => 'Admin',
    'menu' => [
        [
            "name" => "Tổng quan",
            "route" => 'dashboard'
        ],
        [
            "name" => "Crawler bài viết",
            "route" => 'poststemp'
        ]
    ],
    'table_prefix' => 'admin_',
    'route_prefix' => 'admin'
];
