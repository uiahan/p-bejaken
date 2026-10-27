<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <title>@yield('title', 'Cap Jamu Raden')</title>
</head>
<body>
    <main>
        @yield('content')
    </main>

    @include('components.toaster')

    @stack('scripts')
</body>
</html>