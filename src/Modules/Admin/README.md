## Installation

Require this package with composer. It is recommended to only require the package for development.

```shell
composer require toanld/laravel-module-admin-inertiajs-vue
```

### Cài đặt module theo các bước


####sẽ hiển thị ra các package php và js cần cài thì cài đặt nó
```

npm -D install @inertiajs/inertia
npm -D install @inertiajs/progress
npm -D install @inertiajs/vue3
npm -D install @popperjs/core
npm -D install sass
npm -D install html2canvas
npm -D install nprogress
npm -D install postcss
npm -D install postcss-import
npm -D install @tailwindcss/forms
npm -D install @tailwindcss/line-clamp
npm -D install tailwindcss
npm -D install lodash
npm -D install uuid
npm -D install fuse.js
npm -D install autoprefixer
npm -D install @vitejs/plugin-vue
npm -D install vue
```
```shell
php artisan install:admin
```

### Tạo database
```shell
php artisan migrate
```
### sửa .env 
```shell
APP_URL=http://127.0.0.1
```

### Tạo user đăng nhập admin

```shell
php artisan admin:create-user
```

### Tạo page trong admin
```shell
php artisan make:admin-page
```

### Tạo dữ liệu bài viết mẫu để code
```shell
php artisan post:example
```

### Thêm cấu hình configuration trong datatabase thì thêm vào file config.php của module biến db_configs như ví dụ dưới đây
```
return [
    'name' => 'Web',
    'db_configs' => [
        "app" => [
            "label" => "Thông tin ứng dụng",
            'type' => 'json',
            "value" => [
                "android" => 'https://play.google.com/store/apps/details?id=scandict.com',
                "ios"   => 'https://apps.apple.com/vn/app/toeic-max-luy%E1%BB%87n-thi-toeic/id6450526000'
            ]
        ]
    ]
];
```


