## Installation

Require this package with composer. It is recommended to only require the package for development.

```shell
composer require toanld/laravel-module-admin-inertiajs-vue
```

### Cài đặt module theo các bước

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


