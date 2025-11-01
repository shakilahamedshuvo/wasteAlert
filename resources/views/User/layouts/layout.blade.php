<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My App')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#0d1a14] text-gray-100 min-h-screen relative overflow-x-hidden">

    <!-- Background Glow -->
    <div class="absolute inset-0 bg-gradient-to-br from-green-900/10 via-transparent to-green-800/10 blur-3xl"></div>

    <!-- Page Content -->
    <div class="relative z-10 pb-20"> <!-- add padding-bottom to prevent content overlap with navbar -->
        @yield('content')
    </div>

    <!-- Include Bottom Navbar -->
    @include('User.layouts.nav')

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
