@extends('User.layouts.layout')
@section('title', 'Create Complain')
@section('content')
<!-- Submit Complaint Section -->
<section class="pt-8 pb-28 px-5 text-gray-200 bg-gradient-to-b from-transparent to-[#0d1a14]/60">
  <div class="max-w-md mx-auto bg-white/5 backdrop-blur-lg rounded-3xl p-6 shadow-lg border border-white/10">
    
    <!-- Alerts -->
    @if(session('success'))
      <div id="alert-success" class="mb-4 p-4 text-green-800 bg-green-200 rounded-md shadow-md">
        {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div id="alert-error" class="mb-4 p-4 text-red-800 bg-red-200 rounded-md shadow-md">
        {{ session('error') }}
      </div>
    @endif

    <h2 class="text-xl font-semibold text-green-300 mb-2 flex items-center gap-2">
      <i data-lucide="file-text" class="w-5 h-5"></i> Submit Complaint
    </h2>
    <p class="text-sm text-gray-400 mb-6">
      Please describe your issue below. Our team will review it and respond shortly.
    </p>

    <form action="{{ route('complain.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
      @csrf
      <!-- Complaint Type -->
      <div>
        <label class="block text-sm mb-2 text-gray-300">Complaint Type</label>
        <select name="complaint_type" required class="w-full bg-white/10 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
          <option value="">Select type</option>
          <option value="Garbage Overflow">Garbage Overflow</option>
          <option value="Uncollected Waste">Uncollected Waste</option>
          <option value="Illegal Dumping">Illegal Dumping</option>
          <option value="Others">Others</option>
        </select>
        @error('complaint_type')
          <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Location -->
      <div>
        <label class="block text-sm mb-2 text-gray-300 flex items-center justify-between">
          <span>Location</span>
          <button type="button" onclick="detectLocation()" class="text-xs text-green-400 hover:text-green-300 flex items-center gap-1">
            <i data-lucide="map-pin" class="w-3 h-3"></i>
            Auto-detect GPS
          </button>
        </label>
        <div class="relative">
          <input type="text" name="location" id="location" placeholder="Start typing address..." required 
                 class="w-full bg-white/10 border border-white/10 rounded-xl px-4 py-3 pr-24 text-sm focus:outline-none focus:ring-2 focus:ring-green-400"
                 oninput="searchLocations()"
                 onfocus="showSuggestions()"
                 autocomplete="off">
          <button type="button" onclick="geocodeUserInput()" 
                  class="absolute right-2 top-1/2 -translate-y-1/2 bg-green-500/20 hover:bg-green-500/30 px-3 py-1 rounded-lg text-xs text-green-400 transition flex items-center gap-1">
            <i data-lucide="search" class="w-3 h-3"></i>
            Find
          </button>
          <div id="location-loading" class="absolute right-20 top-1/2 -translate-y-1/2 hidden">
            <i data-lucide="loader" class="w-4 h-4 animate-spin text-yellow-400"></i>
          </div>
          
          <!-- Suggestions Dropdown -->
          <div id="location-suggestions" class="absolute top-full left-0 right-0 mt-2 bg-[#0d1a14] border border-green-500/30 rounded-xl shadow-2xl max-h-64 overflow-y-auto z-50 hidden">
            <!-- Suggestions will be populated here -->
          </div>
        </div>
        <p class="text-xs text-gray-500 mt-1">
          <i data-lucide="info" class="w-3 h-3 inline"></i>
          Type to see suggestions, or click "Auto-detect GPS"
        </p>
        @error('location')
          <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Hidden GPS Coordinates -->
      <input type="hidden" name="latitude" id="latitude">
      <input type="hidden" name="longitude" id="longitude">

      <!-- GPS Status Indicator -->
      <div id="gps-status" class="flex items-center gap-2 text-xs">
        <i data-lucide="loader" class="w-4 h-4 animate-spin text-yellow-400"></i>
        <span class="text-gray-400">Getting your GPS coordinates...</span>
      </div>

      <!-- Description -->
      <div>
        <label class="block text-sm mb-2 text-gray-300">Description</label>
        <textarea name="description" rows="4" placeholder="Describe the issue..." required class="w-full bg-white/10 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-400"></textarea>
        @error('description')
          <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Image Upload -->
      <div>
        <label class="block text-sm mb-2 text-gray-300">Upload Image (optional)</label>
        <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-green-500/30 rounded-2xl cursor-pointer hover:bg-white/5 transition">
          <i data-lucide="image" class="w-8 h-8 text-green-400 mb-1"></i>
          <span class="text-xs text-gray-400" id="file-name">Click to upload</span>
          <input type="file" name="image" accept="image/*" class="hidden" onchange="document.getElementById('file-name').textContent = this.files[0]?.name || 'Click to upload'">
        </label>
        @error('image')
          <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Submit Button -->
      <button type="submit" class="w-full bg-gradient-to-br from-[#88e7b5] to-[#5fd49a] text-black font-semibold py-3 rounded-2xl shadow-lg hover:scale-[1.02] transition-transform">
        Submit Complaint
      </button>
    </form>

  </div>
</section>

<script>
  lucide.createIcons();

  let searchTimeout = null;
  let suggestionsData = [];

  // Close suggestions when clicking outside
  document.addEventListener('click', function(e) {
    const locationInput = document.getElementById('location');
    const suggestions = document.getElementById('location-suggestions');
    
    if (!locationInput.contains(e.target) && !suggestions.contains(e.target)) {
      hideSuggestions();
    }
  });

  // Function to search locations (debounced)
  function searchLocations() {
    clearTimeout(searchTimeout);
    
    const query = document.getElementById('location').value.trim();
    
    if (query.length < 3) {
      hideSuggestions();
      return;
    }

    searchTimeout = setTimeout(() => {
      fetchLocationSuggestions(query);
    }, 500); // Wait 500ms after user stops typing
  }

  // Function to fetch location suggestions
  function fetchLocationSuggestions(query) {
    document.getElementById('location-loading').classList.remove('hidden');
    lucide.createIcons();

    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&addressdetails=1`)
      .then(response => response.json())
      .then(data => {
        document.getElementById('location-loading').classList.add('hidden');
        
        suggestionsData = data;
        displaySuggestions(data);
      })
      .catch(error => {
        document.getElementById('location-loading').classList.add('hidden');
        console.error('Error fetching suggestions:', error);
      });
  }

  // Function to display suggestions
  function displaySuggestions(data) {
    const suggestionsDiv = document.getElementById('location-suggestions');
    
    if (!data || data.length === 0) {
      suggestionsDiv.innerHTML = `
        <div class="p-4 text-center text-gray-400 text-sm">
          <i data-lucide="search-x" class="w-5 h-5 mx-auto mb-2"></i>
          No locations found
        </div>
      `;
      suggestionsDiv.classList.remove('hidden');
      lucide.createIcons();
      return;
    }

    let html = '';
    data.forEach((item, index) => {
      const address = item.address || {};
      const displayParts = [];
      
      if (address.road) displayParts.push(address.road);
      if (address.suburb) displayParts.push(address.suburb);
      if (address.city) displayParts.push(address.city);
      if (address.state) displayParts.push(address.state);
      if (address.country) displayParts.push(address.country);
      
      const displayName = displayParts.join(', ') || item.display_name;
      
      html += `
        <div class="suggestion-item p-3 hover:bg-green-500/10 cursor-pointer border-b border-white/5 last:border-0 transition" 
             onclick="selectSuggestion(${index})">
          <div class="flex items-start gap-2">
            <i data-lucide="map-pin" class="w-4 h-4 text-green-400 mt-0.5 flex-shrink-0"></i>
            <div class="flex-1 min-w-0">
              <p class="text-sm text-gray-200 truncate">${displayName}</p>
              <p class="text-xs text-gray-500 mt-0.5">${item.type || 'Location'}</p>
            </div>
          </div>
        </div>
      `;
    });

    suggestionsDiv.innerHTML = html;
    suggestionsDiv.classList.remove('hidden');
    lucide.createIcons();
  }

  // Function to select a suggestion
  function selectSuggestion(index) {
    const item = suggestionsData[index];
    
    if (!item) return;
    
    const address = item.address || {};
    const displayParts = [];
    
    if (address.road) displayParts.push(address.road);
    if (address.suburb) displayParts.push(address.suburb);
    if (address.city) displayParts.push(address.city);
    if (address.state) displayParts.push(address.state);
    
    const displayName = displayParts.slice(0, 3).join(', ') || item.display_name;
    
    // Set location input
    document.getElementById('location').value = displayName;
    
    // Set GPS coordinates
    document.getElementById('latitude').value = item.lat;
    document.getElementById('longitude').value = item.lon;
    
    // Update GPS status
    const gpsStatus = document.getElementById('gps-status');
    gpsStatus.innerHTML = `
      <i data-lucide="map-pin" class="w-4 h-4 text-green-400"></i>
      <span class="text-green-400">Location selected: ${parseFloat(item.lat).toFixed(6)}, ${parseFloat(item.lon).toFixed(6)}</span>
    `;
    lucide.createIcons();
    
    // Hide suggestions
    hideSuggestions();
    
    console.log("Selected location:", displayName, item.lat, item.lon);
  }

  // Function to show suggestions
  function showSuggestions() {
    const suggestions = document.getElementById('location-suggestions');
    if (suggestions.innerHTML.trim() !== '') {
      suggestions.classList.remove('hidden');
    }
  }

  // Function to hide suggestions
  function hideSuggestions() {
    document.getElementById('location-suggestions').classList.add('hidden');
  }

  // Function to get address from coordinates (Reverse Geocoding)
  function getAddressFromCoords(lat, lng) {
    document.getElementById('location-loading').classList.remove('hidden');
    lucide.createIcons();
    
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
      .then(response => response.json())
      .then(data => {
        document.getElementById('location-loading').classList.add('hidden');
        
        if (data && data.display_name) {
          const address = data.address || {};
          const parts = [];
          
          if (address.road) parts.push(address.road);
          if (address.suburb) parts.push(address.suburb);
          if (address.city) parts.push(address.city);
          if (address.state) parts.push(address.state);
          
          const shortAddress = parts.slice(0, 3).join(', ') || data.display_name;
          document.getElementById('location').value = shortAddress;
          
          console.log("Address detected:", shortAddress);
        } else {
          document.getElementById('location').placeholder = "Enter location manually";
        }
      })
      .catch(error => {
        document.getElementById('location-loading').classList.add('hidden');
        console.error('Error fetching address:', error);
        document.getElementById('location').placeholder = "Enter location manually";
      });
  }

  // Function to geocode user input (Forward Geocoding)
  function geocodeUserInput() {
    const locationInput = document.getElementById('location').value.trim();
    
    if (!locationInput || locationInput.length < 3) {
      return; // Don't search for very short inputs
    }

    document.getElementById('location-loading').classList.remove('hidden');
    lucide.createIcons();
    
    // Using Nominatim forward geocoding API
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(locationInput)}&limit=1`)
      .then(response => response.json())
      .then(data => {
        document.getElementById('location-loading').classList.add('hidden');
        
        if (data && data.length > 0) {
          const result = data[0];
          const lat = parseFloat(result.lat);
          const lng = parseFloat(result.lon);
          
          // Set GPS coordinates
          document.getElementById('latitude').value = lat;
          document.getElementById('longitude').value = lng;
          
          // Update GPS status
          const gpsStatus = document.getElementById('gps-status');
          gpsStatus.innerHTML = `
            <i data-lucide="map-pin" class="w-4 h-4 text-green-400"></i>
            <span class="text-green-400">Coordinates found: ${lat.toFixed(6)}, ${lng.toFixed(6)}</span>
          `;
          lucide.createIcons();
          
          console.log("Geocoded coordinates:", lat, lng);
          console.log("Full address:", result.display_name);
        } else {
          // No results found
          const gpsStatus = document.getElementById('gps-status');
          gpsStatus.innerHTML = `
            <i data-lucide="alert-circle" class="w-4 h-4 text-orange-400"></i>
            <span class="text-orange-400">Location not found. Try "Auto-detect GPS" or enter a valid address.</span>
          `;
          lucide.createIcons();
        }
      })
      .catch(error => {
        document.getElementById('location-loading').classList.add('hidden');
        console.error('Error geocoding location:', error);
        
        const gpsStatus = document.getElementById('gps-status');
        gpsStatus.innerHTML = `
          <i data-lucide="alert-circle" class="w-4 h-4 text-red-400"></i>
          <span class="text-red-400">Error finding location. Please try again.</span>
        `;
        lucide.createIcons();
      });
  }

  // Function to detect location (can be called manually)
  function detectLocation() {
    if (!navigator.geolocation) {
      alert("Geolocation is not supported by your browser.");
      return;
    }

    document.getElementById('location-loading').classList.remove('hidden');
    lucide.createIcons();

    navigator.geolocation.getCurrentPosition(
      (position) => {
        document.getElementById('latitude').value = position.coords.latitude;
        document.getElementById('longitude').value = position.coords.longitude;
        
        // Update GPS status
        const gpsStatus = document.getElementById('gps-status');
        gpsStatus.innerHTML = `
          <i data-lucide="map-pin" class="w-4 h-4 text-green-400"></i>
          <span class="text-green-400">GPS: ${position.coords.latitude.toFixed(6)}, ${position.coords.longitude.toFixed(6)}</span>
        `;
        lucide.createIcons();
        
        // Get address from coordinates
        getAddressFromCoords(position.coords.latitude, position.coords.longitude);
        
        console.log("Latitude:", position.coords.latitude);
        console.log("Longitude:", position.coords.longitude);
      },
      (error) => {
        document.getElementById('location-loading').classList.add('hidden');
        console.error("Error getting location:", error.message);
        
        // Update GPS status on error
        const gpsStatus = document.getElementById('gps-status');
        gpsStatus.innerHTML = `
          <i data-lucide="alert-circle" class="w-4 h-4 text-red-400"></i>
          <span class="text-red-400">Unable to get GPS. Please enter location manually.</span>
        `;
        lucide.createIcons();
      },
      { 
        enableHighAccuracy: true,
        timeout: 10000,
        maximumAge: 0
      }
    );
  }

  // Auto-detect location on page load
  detectLocation();

  // Auto-hide alerts after 4 seconds
  setTimeout(() => {
    const successAlert = document.getElementById('alert-success');
    const errorAlert = document.getElementById('alert-error');
    if(successAlert) successAlert.style.display = 'none';
    if(errorAlert) errorAlert.style.display = 'none';
  }, 4000);
</script>
@endsection