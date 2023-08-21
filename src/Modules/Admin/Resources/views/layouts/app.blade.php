<!DOCTYPE html>
<html class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @routes
    <script>
        Ziggy.url = "{!! config('app.url') !!}";
        console.log(Ziggy);
    </script>
    @vite('Modules/Admin/Resources/assets/css/app.scss')
    @vite('Modules/Admin/Resources/Vuejs/app.js')
    @inertiaHead
</head>
<body class="font-sans leading-none text-gray-700 antialiased">
    @inertia
</body>
</html>
