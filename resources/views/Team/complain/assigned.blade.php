@extends('Team.Layouts.layout')
@section('title', 'Assigned Complaints')
@section('content')

<!-- Map Modal -->
<div id="map-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
  <div class="bg-[#0d1a14] rounded-3xl w-full max-w-4xl max-h-[90vh] overflow-hidden border border-green-500/30">
    <!-- Modal Header -->
    <div class="bg-gradient-to-r from-green-500/20 to-green-600/20 p-4 border-b border-white/10 flex items-center justify-between">
      <div>
        <h3 class="text-xl font-bold text-green-300 flex items-center gap-2">
          <i data-lucide="navigation" class="w-5 h-5"></i>
          Live Navigation
        </h3>
        <p class="text-sm text-gray-400" id="modal-location"></p>
      </div>
      <button onclick="closeMapModal()" class="bg-red-500/20 hover:bg-red-500/30 p-2 rounded-xl transition">
        <i data-lucide="x" class="w-5 h-5 text-red-400"></i>
      </button>
    </div>

    <!-- Distance & ETA Info -->
    <div class="grid grid-cols-3 gap-4 p-4 bg-white/5">
      <div class="text-center">
        <div class="text-2xl font-bold text-green-400" id="distance-display">--</div>
        <div class="text-xs text-gray-400">Distance</div>
      </div>
      <div class="text-center">
        <div class="text-2xl font-bold text-blue-400" id="eta-display">--</div>
        <div class="text-xs text-gray-400">ETA</div>
      </div>
      <div class="text-center">
        <div class="text-2xl font-bold text-purple-400" id="speed-display">--</div>
        <div class="text-xs text-gray-400">Speed</div>
      </div>
    </div>

    <!-- Map Container -->
    <div id="map-container" class="w-full h-96 bg-gray-900"></div>

    <!-- Navigation Controls -->
    <div class="p-4 bg-white/5 flex gap-3">
      <button onclick="openInGoogleMaps()" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-xl font-semibold transition flex items-center justify-center gap-2">
        <i data-lucide="external-link" class="w-4 h-4"></i>
        Open in Google Maps
      </button>
      <button onclick="markCompleted()" class="flex-1 bg-green-500 hover:bg-green-600 text-white py-3 rounded-xl font-semibold transition flex items-center justify-center gap-2">
        <i data-lucide="check-circle" class="w-4 h-4"></i>
        Mark Completed
      </button>
    </div>
  </div>
</div>

<!-- Header Section -->
<div class="px-5 pt-8 pb-4">
  <div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-2">
      <h1 class="text-2xl font-bold text-green-300 flex items-center gap-2">
        <i data-lucide="clipboard-list" class="w-6 h-6"></i>
        My Assigned Complaints
      </h1>
      <span class="bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-sm font-semibold">
        {{ $complaints->count() }} Total
      </span>
    </div>
    <p class="text-gray-400 text-sm">Manage and track your assigned waste complaints</p>
  </div>
</div>

<!-- Complaints List Section -->
<section class="px-5 pb-28">
  <div class="max-w-4xl mx-auto space-y-4">
    
    @if($complaints->isEmpty())
      <!-- Empty State -->
      <div class="bg-white/5 backdrop-blur-lg rounded-3xl p-12 text-center border border-white/10">
        <div class="bg-green-500/20 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
          <i data-lucide="inbox" class="w-10 h-10 text-green-400"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-300 mb-2">No Complaints Assigned</h3>
        <p class="text-gray-400 text-sm">You don't have any assigned complaints at the moment.</p>
      </div>
    @else
      
      @foreach($complaints as $complaint)
      <!-- Complaint Card -->
      <div class="bg-white/5 backdrop-blur-lg rounded-3xl p-5 border border-white/10 hover:border-green-500/30 transition-all duration-300 hover:shadow-lg hover:shadow-green-500/10">
        
        <!-- Header -->
        <div class="flex items-start justify-between mb-4">
          <div class="flex-1">
            <div class="flex items-center gap-2 mb-1">
              <span class="px-3 py-1 rounded-full text-xs font-semibold
                @if($complaint->status === 'pending') bg-yellow-500/20 text-yellow-400
                @elseif($complaint->status === 'accepted') bg-blue-500/20 text-blue-400
                @elseif($complaint->status === 'in-progress') bg-purple-500/20 text-purple-400
                @elseif($complaint->status === 'completed') bg-green-500/20 text-green-400
                @else bg-gray-500/20 text-gray-400
                @endif">
                {{ ucfirst($complaint->status) }}
              </span>
              
              @if($complaint->is_recycleable)
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-500/20 text-green-400">
                  <i data-lucide="recycle" class="w-3 h-3 inline"></i> Recyclable
                </span>
              @endif
            </div>
            
            <h3 class="text-lg font-bold text-green-300 flex items-center gap-2">
              <i data-lucide="alert-triangle" class="w-5 h-5"></i>
              {{ $complaint->complaint_type }}
            </h3>
          </div>
          
          <button class="bg-white/10 hover:bg-white/20 p-2 rounded-xl transition">
            <i data-lucide="more-vertical" class="w-5 h-5"></i>
          </button>
        </div>

        <!-- Description -->
        <p class="text-gray-300 text-sm mb-4">{{ $complaint->description }}</p>

        <!-- Details Grid -->
        <div class="grid grid-cols-2 gap-3 mb-4">
          <!-- Location -->
          <div class="bg-white/5 rounded-xl p-3">
            <div class="flex items-center gap-2 text-gray-400 text-xs mb-1">
              <i data-lucide="map-pin" class="w-3 h-3"></i>
              Location
            </div>
            <p class="text-sm font-semibold text-gray-200" id="location-{{ $complaint->id }}">
              {{ $complaint->location }}
            </p>
            <p class="text-xs text-gray-400 mt-1" id="full-address-{{ $complaint->id }}">
              Loading address...
            </p>
          </div>

          <!-- Reporter -->
          <div class="bg-white/5 rounded-xl p-3">
            <div class="flex items-center gap-2 text-gray-400 text-xs mb-1">
              <i data-lucide="user" class="w-3 h-3"></i>
              Reported By
            </div>
            <p class="text-sm font-semibold text-gray-200">{{ $complaint->user->name ?? 'Unknown' }}</p>
          </div>

          <!-- Date -->
          <div class="bg-white/5 rounded-xl p-3">
            <div class="flex items-center gap-2 text-gray-400 text-xs mb-1">
              <i data-lucide="calendar" class="w-3 h-3"></i>
              Date Reported
            </div>
            <p class="text-sm font-semibold text-gray-200">{{ $complaint->created_at->format('M d, Y') }}</p>
          </div>

          <!-- GPS Coordinates -->
          <div class="bg-white/5 rounded-xl p-3">
            <div class="flex items-center gap-2 text-gray-400 text-xs mb-1">
              <i data-lucide="navigation" class="w-3 h-3"></i>
              Coordinates
            </div>
            <p class="text-xs font-mono text-gray-200">{{ number_format($complaint->latitude, 4) }}, {{ number_format($complaint->longitude, 4) }}</p>
          </div>
        </div>

        <!-- Image Preview -->
        @if($complaint->image)
        <div class="mb-4">
          <img src="{{ asset('storage/' . $complaint->image) }}" 
               alt="Complaint Image" 
               class="w-full h-48 object-cover rounded-2xl border border-white/10">
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex gap-2">
          <button onclick="viewOnMap({{ $complaint->latitude }}, {{ $complaint->longitude }})" 
                  class="flex-1 bg-gradient-to-br from-blue-500 to-blue-600 text-white py-3 rounded-xl font-semibold hover:scale-[1.02] transition-transform flex items-center justify-center gap-2">
            <i data-lucide="map" class="w-4 h-4"></i>
            View on Map
          </button>
          
          @if($complaint->status === 'pending')
          <button onclick="startWork({{ $complaint->id }}, {{ $complaint->latitude }}, {{ $complaint->longitude }}, '{{ $complaint->location }}')" 
                  class="flex-1 bg-gradient-to-br from-green-500 to-green-600 text-white py-3 rounded-xl font-semibold hover:scale-[1.02] transition-transform flex items-center justify-center gap-2">
            <i data-lucide="play-circle" class="w-4 h-4"></i>
            Start Work
          </button>
          @elseif($complaint->status === 'in-progress')
          <button onclick="openLiveMap({{ $complaint->id }}, {{ $complaint->latitude }}, {{ $complaint->longitude }}, '{{ $complaint->location }}')" 
                  class="flex-1 bg-gradient-to-br from-purple-500 to-purple-600 text-white py-3 rounded-xl font-semibold hover:scale-[1.02] transition-transform flex items-center justify-center gap-2 animate-pulse">
            <i data-lucide="navigation" class="w-4 h-4"></i>
            Live Navigation
          </button>
          <button onclick="updateStatus({{ $complaint->id }}, 'completed')" 
                  class="flex-1 bg-gradient-to-br from-green-500 to-green-600 text-white py-3 rounded-xl font-semibold hover:scale-[1.02] transition-transform flex items-center justify-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            Mark Complete
          </button>
          @elseif($complaint->status === 'completed')
          <div class="flex-1 bg-green-500/20 text-green-400 py-3 rounded-xl font-semibold flex items-center justify-center gap-2">
            <i data-lucide="check-circle-2" class="w-4 h-4"></i>
            Completed
          </div>
          @endif
        </div>

      </div>
      @endforeach

    @endif

  </div>
</section>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script>
  lucide.createIcons();

  let map = null;
  let currentComplaintId = null;
  let targetLat = null;
  let targetLng = null;
  let targetLocation = null;
  let watchId = null;
  let userMarker = null;
  let targetMarker = null;
  let routeLine = null;

  // Load addresses for all complaints
  document.addEventListener('DOMContentLoaded', function() {
    @foreach($complaints as $complaint)
      getAddress({{ $complaint->latitude }}, {{ $complaint->longitude }}, {{ $complaint->id }});
    @endforeach
  });

  // Function to start work
  function startWork(complainId, lat, lng, location) {
    if (!confirm('Start working on this complaint?')) {
      return;
    }
    
    updateStatus(complainId, 'in-progress');
  }

  // Function to open live map
  function openLiveMap(complainId, lat, lng, location) {
    currentComplaintId = complainId;
    targetLat = lat;
    targetLng = lng;
    targetLocation = location;
    
    document.getElementById('modal-location').textContent = location;
    document.getElementById('map-modal').classList.remove('hidden');
    
    // Initialize map
    setTimeout(() => {
      initMap();
      startTracking();
    }, 100);
    
    lucide.createIcons();
  }

  // Function to close map modal
  function closeMapModal() {
    document.getElementById('map-modal').classList.add('hidden');
    if (watchId) {
      navigator.geolocation.clearWatch(watchId);
      watchId = null;
    }
    if (map) {
      map.remove();
      map = null;
    }
  }

  // Initialize Leaflet map
  function initMap() {
    if (map) {
      map.remove();
    }

    map = L.map('map-container').setView([targetLat, targetLng], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add target marker (complaint location)
    targetMarker = L.marker([targetLat, targetLng], {
      icon: L.divIcon({
        className: 'custom-marker',
        html: `<div style="background: #ef4444; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 4px 6px rgba(0,0,0,0.3);">
                 <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                   <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                   <circle cx="12" cy="10" r="3"></circle>
                 </svg>
               </div>`,
        iconSize: [40, 40],
        iconAnchor: [20, 40]
      })
    }).addTo(map);

    targetMarker.bindPopup(`<b>${targetLocation}</b><br>Complaint Location`).openPopup();
  }

  // Start tracking user location
  function startTracking() {
    if (!navigator.geolocation) {
      alert('Geolocation is not supported by your browser');
      return;
    }

    watchId = navigator.geolocation.watchPosition(
      (position) => {
        const userLat = position.coords.latitude;
        const userLng = position.coords.longitude;
        const speed = position.coords.speed || 0;

        updateUserLocation(userLat, userLng);
        calculateDistance(userLat, userLng);
        updateSpeed(speed);
      },
      (error) => {
        console.error('Geolocation error:', error);
      },
      {
        enableHighAccuracy: true,
        maximumAge: 1000,
        timeout: 5000
      }
    );
  }

  // Update user marker on map
  function updateUserLocation(lat, lng) {
    if (userMarker) {
      userMarker.setLatLng([lat, lng]);
    } else {
      userMarker = L.marker([lat, lng], {
        icon: L.divIcon({
          className: 'custom-marker',
          html: `<div style="background: #22c55e; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 4px 6px rgba(0,0,0,0.3);">
                   <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                     <circle cx="12" cy="12" r="10"></circle>
                     <circle cx="12" cy="12" r="3" fill="white"></circle>
                   </svg>
                 </div>`,
          iconSize: [40, 40],
          iconAnchor: [20, 20]
        })
      }).addTo(map);
      
      userMarker.bindPopup('<b>You are here</b>');
    }

    // Draw route line
    if (routeLine) {
      map.removeLayer(routeLine);
    }
    
    routeLine = L.polyline([
      [lat, lng],
      [targetLat, targetLng]
    ], {
      color: '#22c55e',
      weight: 4,
      opacity: 0.7,
      dashArray: '10, 10'
    }).addTo(map);

    // Fit map to show both markers
    const bounds = L.latLngBounds([[lat, lng], [targetLat, targetLng]]);
    map.fitBounds(bounds, { padding: [50, 50] });
  }

  // Calculate distance and ETA
  function calculateDistance(userLat, userLng) {
    const R = 6371; // Earth's radius in km
    const dLat = (targetLat - userLat) * Math.PI / 180;
    const dLon = (targetLng - userLng) * Math.PI / 180;
    
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
              Math.cos(userLat * Math.PI / 180) * Math.cos(targetLat * Math.PI / 180) *
              Math.sin(dLon/2) * Math.sin(dLon/2);
    
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    const distance = R * c;

    document.getElementById('distance-display').textContent = distance.toFixed(2) + ' km';

    // Calculate ETA (assuming average speed of 30 km/h)
    const avgSpeed = 30;
    const eta = (distance / avgSpeed) * 60; // in minutes
    document.getElementById('eta-display').textContent = Math.ceil(eta) + ' min';
  }

  // Update speed display
  function updateSpeed(speed) {
    const speedKmh = speed * 3.6; // Convert m/s to km/h
    document.getElementById('speed-display').textContent = speedKmh.toFixed(1) + ' km/h';
  }

  // Open in Google Maps
  function openInGoogleMaps() {
    window.open(`https://www.google.com/maps/dir/?api=1&destination=${targetLat},${targetLng}`, '_blank');
  }

  // Mark complaint as completed
  function markCompleted() {
    if (!confirm('Mark this complaint as completed?')) {
      return;
    }
    
    updateStatus(currentComplaintId, 'completed');
  }

  // Function to get real address from coordinates
  function getAddress(lat, lng, complainId) {
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
      .then(response => response.json())
      .then(data => {
        const fullAddressElement = document.getElementById('full-address-' + complainId);
        
        if (data && data.display_name) {
          const address = data.address || {};
          const parts = [];
          
          if (address.road) parts.push(address.road);
          if (address.suburb) parts.push(address.suburb);
          if (address.city) parts.push(address.city);
          if (address.state) parts.push(address.state);
          if (address.country) parts.push(address.country);
          
          const shortAddress = parts.slice(0, 3).join(', ') || data.display_name;
          fullAddressElement.textContent = shortAddress;
          fullAddressElement.classList.remove('text-gray-400');
          fullAddressElement.classList.add('text-green-400');
        } else {
          fullAddressElement.textContent = `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
        }
      })
      .catch(error => {
        console.error('Error fetching address:', error);
        const fullAddressElement = document.getElementById('full-address-' + complainId);
        fullAddressElement.textContent = `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
      });
  }

  function viewOnMap(lat, lng) {
    window.open(`https://www.google.com/maps?q=${lat},${lng}`, '_blank');
  }

  function updateStatus(complainId, status) {
    fetch(`/team/complaint/${complainId}/update-status`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        location.reload();
      } else {
        alert('Failed to update status');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('An error occurred');
    });
  }
</script>

@endsection
