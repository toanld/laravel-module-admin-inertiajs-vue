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
        '4' => [
            'name' => 'demo',
            'route' => 'demo',
        ],
        '5' => [
            'name' => 'Cấu hình chung',
            'route' => 'configurations',
        ],
    ],
    'table_prefix' => 'admin_',
    'route_prefix' => 'admin',
    'watermark' => env('WATERMARK','Anduoc.vn')
];
