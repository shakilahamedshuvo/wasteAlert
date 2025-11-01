<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ Auth::id() }}">
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
    @include('team.layouts.nav')
    <script>
        function sendLocationToServer() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    fetch('{{ url("team/update-location") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Location sent:', data);
                    })
                    .catch(error => {
                        console.error('Error sending location:', error);
                    });
                }, function(error) {
                    console.error('Geolocation error:', error.message);
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                });
            } else {
                console.error('Geolocation is not supported by this browser.');
            }
        }

        // Send location every 10 seconds (adjust as needed)
        setInterval(sendLocationToServer, 10000);

        // Send once immediately when page loads
        sendLocationToServer();
    </script>
    <script>
        lucide.createIcons();
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const userIdMeta = document.querySelector('meta[name="user-id"]');
        const userId = userIdMeta ? userIdMeta.content : '';
        if (window.Echo && userId) {
            window.Echo.private(`App.Models.User.${userId}`)
                .notification((notification) => {
                    console.log(notification);
                    alert(notification.message); // popup
                });
        }
    });
    </script>
</body>
</html>
</script>
