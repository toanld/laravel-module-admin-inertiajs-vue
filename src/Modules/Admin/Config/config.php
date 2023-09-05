<?php

return [
    'name' => 'Admin',
    'menu' => [
        '0' => [
            'name' => 'Tổng quan',
            'route' => 'dashboard',
        ],
        '1' => [
            'name' => 'Bài viết',
            'route' => 'posts',
        ],
        '3' => [
            'name' => 'Danh mục bài viết',
            'route' => 'categoryposts',
        ],
    ],
    'table_prefix' => 'admin_',
    'route_prefix' => 'admin',
];
