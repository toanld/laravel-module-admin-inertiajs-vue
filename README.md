## Installation

Introducing toanld/laravel-module-admin-inertiajs-vue
Are you looking to separate your admin panel from your main application and leverage the power of Vue.js on the frontend with Laravel on the backend? Look no further! With toanld/laravel-module-admin-inertiajs-vue, you can have a fully-integrated admin module up and running in no time.

### Key Benefits:
Rapid Project Creation: toanld/laravel-module-admin-inertiajs-vue empowers you to create a standalone admin project without starting from scratch. Save time by eliminating the need to develop your admin interface from the ground up.

Vue.js and Laravel Integration: We've seamlessly integrated Vue.js and Laravel, offering you the best of both development worlds. Use Laravel to manage data and Vue.js to display it smoothly.

Inertia.js Integration: With Inertia.js support, toanld/laravel-module-admin-inertiajs-vue enables you to build a real-time admin interface without page reloads. Enhance user experiences and optimize application performance.

Customization and Scalability: Designed for easy customization and extensibility, toanld/laravel-module-admin-inertiajs-vue allows you to tailor the admin interface to your project's specific needs. Modify the UI, add new features, and adjust it as required.

If you're seeking a swift and efficient way to kickstart your Vue.js and Laravel admin interface, toanld/laravel-module-admin-inertiajs-vue is the top choice. Explore and experience its convenience and power today!

Feel free to adapt and use this markdown introduction for your package. You can replace toanld/laravel-module-admin-inertiajs-vue with your package name, and further customize it to your liking.

```shell
composer require toanld/laravel-module-admin-inertiajs-vue
```

### Cài đặt module theo các bước


####sẽ hiển thị ra các package php và js cần cài thì cài đặt nó
```
composer require barryvdh/laravel-debugbar --dev
composer require nwidart/laravel-modules
composer require ryannielson/meta
composer require toanld/ziggy
composer require toanld/laravel-debug-to-sql
composer require toanld/modules-inertia
composer require toanld/multi-relationships
composer require inertiajs/inertia-laravel
composer require kalnoy/nestedset
composer require intervention/image
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


