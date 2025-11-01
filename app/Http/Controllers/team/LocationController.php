<?php

namespace App\Http\Controllers\team;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    public function updateLocation(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

       
        $user = $request->user();

        // Update the user's location
        $user->latitude = $request->input('latitude');
        $user->longitude = $request->input('longitude');
        $user->save();


        return response()->json(['message' => 'Location updated successfully'], 200);
    }
}
