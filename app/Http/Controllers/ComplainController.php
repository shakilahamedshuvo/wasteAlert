<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Complain;
use App\Models\User;

class ComplainController extends Controller
{
    public function showComplainForm(Request $request)
    {
        return view('User.Complain.create');
    }

    public function storeComplain(Request $request)
    {
        $request->validate([
            'complaint_type' => 'required|string',
            'location' => 'required|string',
            'description' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        $predictionResult = null;
        $confidence = null;

        // ✅ Upload image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('complaints', 'public');
            $fullImagePath = storage_path('app/public/' . $imagePath);

            // ✅ Call Flask API with proper resource handling
            try {
                $imageResource = fopen($fullImagePath, 'r');
                $response = Http::attach(
                    'image', $imageResource, basename($fullImagePath)
                )->post('http://127.0.0.1:5000/predict');
                fclose($imageResource); // Close file handle

                if ($response->successful()) {
                    $data = $response->json();
                    $predictionResult = $data['class'] ?? null;
                    $confidence = $data['confidence'] ?? null;
                } else {
                    Log::error('Flask API error', ['response' => $response->body()]);
                }
            } catch (\Exception $e) {
                Log::error('Flask API connection failed', ['error' => $e->getMessage()]);
            }
        }
        
        // ✅ Determine recyclability (based on prediction)
        $is_recyclable = 0;
        if ($predictionResult === 'Recyclable' && $confidence >= 90) {
            $is_recyclable = 1;
        } else {
            $is_recyclable = 0;
        }
        
        // ✅ Save complaint
        $nearestTeam = User::where('role', 'team')
            ->selectRaw("id, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance", [
                $request->latitude,
                $request->longitude,
                $request->latitude
            ])
            ->orderBy('distance', 'asc')
            ->first();
            
        Complain::create([
            'user_id' => auth()->id(),
            'complaint_type' => $request->complaint_type,
            'location' => $request->location,
            'description' => $request->description,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_recycleable' => $is_recyclable,
            'team_id' => $nearestTeam ? $nearestTeam->id : null,
            'image' => $imagePath,
        ]);

        // ✅ Find nearest team
        
        Log::info('Complaint submitted', [
            'user_id' => auth()->id(),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'prediction' => $predictionResult,
            'confidence' => $confidence,
            'is_recyclable' => $is_recyclable,
            'team_id' => $nearestTeam ? $nearestTeam->id : null,
        ]);

        return back()->with('success', 'Complaint submitted successfully! Prediction: ' . ($predictionResult ?? 'N/A') . ' (' . ($confidence ?? 0) . '%)');
    }
}
