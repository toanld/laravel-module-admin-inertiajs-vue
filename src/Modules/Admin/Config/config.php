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
        '5' => [
            'name' => 'Cấu hình chung',
            'route' => 'configurations',
        ],
        '6' => [
            'name' => 'Dịch web',
            'route' => 'translates',
        ],
        '6' => [
            'name' => 'Trang tĩnh',
            'route' => 'statics',
        ]
    ],
    'table_prefix' => 'admin_',
    'route_prefix' => 'admin',
    'watermark' => env('WATERMARK','Anduoc.vn'),
    'openai_api_key' => env('OPENAI_API_KEY',null),
    'db_configs' => [
        "title" => [
            "label" => "Tiêu đề trang",
            "value" => "Website thương mại điện tử"
        ],
        "description" => [
            "label" => "Mô tả ngắn về trang (150 từ)",
            "value" => "Nội dung mô tả về trang web"
        ],
        "meta" => [
            "label" => "Các thẻ meta cần thiết",
            'value' => [
                "og:site_name" => "Website thương mại điện tử",
                "og:type" => "Website",
                "og:locale" => "vi_VN",
                "og:image" => "vi_VN",
                "copyright" => "",
                "author" => "",
            ]

        ],
        "contact" => [
            "label" => "Thông tin liên hệ, mạng xã hội",
            'value' => [
                "phone" => '190xxxx',
                "zalo" => '098xxxx',
                "fb" => 'https://www.facebook.com/xxxxx',
                "youtube" => 'https://www.facebook.com/xxxxx',
                "email" => 'xxx@gmail.com',
                "map" => 'https://map.google.com/sxxxx',
                'address' => ''
            ]
        ],
        "domain" => [
            "label" => "Tên miền trang web",
            "value" => "anduoc.vn"
        ],
        "openai_api_key" => [
            "label" => "Key API của openai.com",
            "value" => ""
        ],
        "openai_gpt_model" => [
            "label" => "Model API ChatGPT",
            "value" => "gpt-3.5-turbo"
        ],
        "secret_post_api" => [
            "label" => "Secret cho phép đăng bài qua API",
            "value" => ""
        ]

    ]
];
