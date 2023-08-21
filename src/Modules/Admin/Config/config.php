<?php

return [
    'name' => 'Admin',
    'menu' => [
        [
            "name" => "Tổng quan",
            "route" => 'dashboard'
        ],
        [
            "name" => "Bài viết",
            "route" => 'posts'
        ]
    ],
    'table_prefix' => 'admin_',
    'route_prefix' => 'admin'
];
