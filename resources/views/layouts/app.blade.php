<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Mi App')</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    @yield('styles')
</head>
<body>
    <div class="container mt-4">
        @yield('content')
    </div>

    @yield('scripts')
</body>
</html>
