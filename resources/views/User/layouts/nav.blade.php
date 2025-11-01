
<nav class="fixed bottom-0 left-0 w-full bg-white/5 backdrop-blur-xl border-t border-white/10 text-gray-200 z-40">
  <div class="flex justify-around items-center px-4 max-w-md mx-auto h-20 relative">
    
    <!-- Dashboard -->
    <a href="#" class="flex flex-col items-center justify-center text-xs hover:text-green-300 transition-all duration-200 group">
      <div class="w-12 h-12 flex items-center justify-center rounded-2xl group-hover:bg-white/10 transition-all">
        <i data-lucide="layout-dashboard" class="w-6 h-6"></i>
      </div>
      <span class="mt-1 font-medium">Dashboard</span>
    </a>

    <!-- Courses -->
    <a href="{{ route('complain.create.form') }}" class="flex flex-col items-center justify-center text-xs hover:text-green-300 transition-all duration-200 group">
      <div class="w-12 h-12 flex items-center justify-center rounded-2xl group-hover:bg-white/10 transition-all">
        <i data-lucide="file-text" class="w-6 h-6"></i>
      </div>
      <span class="mt-1 font-medium">Submit Complain</span>
    </a>

    <!-- Home Button (Center) -->
    <a href="#" class="flex flex-col items-center justify-center relative -top-6">
      <div class="bg-gradient-to-br from-[#88e7b5] to-[#5fd49a] w-16 h-16 rounded-full flex items-center justify-center shadow-2xl shadow-green-400/30 hover:scale-105 transition-transform duration-200 border-4 border-[#0d1a14]">
        <i data-lucide="home" class="w-7 h-7 text-black"></i>
      </div>
      <span class="text-xs font-medium mt-1">Home</span>
    </a>

    <!-- Pay -->
    <a href="#" class="flex flex-col items-center justify-center text-xs hover:text-green-300 transition-all duration-200 group">
      <div class="w-12 h-12 flex items-center justify-center rounded-2xl group-hover:bg-white/10 transition-all">
        <i data-lucide="map-pin" class="w-6 h-6"></i>
      </div>
      <span class="mt-1 font-medium">Trace Location</span>
    </a>

    <!-- Profile -->
    <a href="#" class="flex flex-col items-center justify-center text-xs hover:text-green-300 transition-all duration-200 group">
      <div class="w-12 h-12 flex items-center justify-center rounded-2xl group-hover:bg-white/10 transition-all">
        <i data-lucide="user" class="w-6 h-6"></i>
      </div>
      <span class="mt-1 font-medium">Profile</span>
    </a>

  </div>
</nav>

<script>
  lucide.createIcons();
</script>
