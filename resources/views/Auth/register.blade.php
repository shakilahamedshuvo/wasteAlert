<!-- resources/views/auth/signup.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#0d1a14] flex items-center justify-center min-h-screen relative overflow-hidden">

    <!-- Background Glow Effect -->
    <div class="absolute inset-0 bg-gradient-to-br from-green-900/10 via-transparent to-green-800/10 blur-3xl"></div>

    <!-- Glass Card -->
    <div class="w-[90%] max-w-sm bg-white/10 backdrop-blur-md border border-white/20 shadow-2xl rounded-2xl p-8 z-10">
        <!-- Help Icon -->
        <div class="flex justify-end">
            <button class="text-gray-300 hover:text-white transition">
                <i data-lucide="help-circle" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Title -->
        <h1 class="text-white text-2xl font-semibold mb-6">Create an account</h1>

        <!-- Form -->
        <form action="{{ route('signUp.submit') }}" method="POST" class="space-y-4">
            @csrf
            <input type="text" name="name" placeholder="Full name"
                   class="w-full bg-white/10 text-gray-100 rounded-md px-4 py-3 outline-none border border-white/20 focus:border-green-400 placeholder-gray-400 backdrop-blur-sm">

            <input type="email" name="email" placeholder="Email"
                   class="w-full bg-white/10 text-gray-100 rounded-md px-4 py-3 outline-none border border-white/20 focus:border-green-400 placeholder-gray-400 backdrop-blur-sm">

            <input type="text" name="phone" placeholder="Phone Number"
                   class="w-full bg-white/10 text-gray-100 rounded-md px-4 py-3 outline-none border border-white/20 focus:border-green-400 placeholder-gray-400 backdrop-blur-sm">

            <input type="password" name="password" placeholder="Password"
                   class="w-full bg-white/10 text-gray-100 rounded-md px-4 py-3 outline-none border border-white/20 focus:border-green-400 placeholder-gray-400 backdrop-blur-sm">

            <button type="submit"
                    class="w-full bg-[#88e7b5] hover:bg-[#74dca3] text-black font-semibold py-3 rounded-full transition duration-300 shadow-lg shadow-green-300/20">
                Sign up
            </button>
        </form>

        <!-- Footer -->
        <p class="text-center text-gray-300 text-sm mt-4">
            Already have an account?
            <a href="#" class="text-green-200 hover:underline">Sign in</a>
        </p>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
