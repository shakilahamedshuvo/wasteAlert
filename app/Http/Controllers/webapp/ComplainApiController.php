<?php

namespace App\Http\Controllers\webapp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Complain;
use App\Models\User;
use Exception;

class ComplainApiController extends Controller
{
    /**
     * Submit a new complaint
     */
    public function store(Request $request)
    {
        try {
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

            // Upload image and get ML prediction
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('complaints', 'public');
                $fullImagePath = storage_path('app/public/' . $imagePath);

                // Call Flask API for prediction
                try {
                    $imageResource = fopen($fullImagePath, 'r');
                    $response = Http::attach(
                        'image', $imageResource, basename($fullImagePath)
                    )->post('http://127.0.0.1:5000/predict');
                    fclose($imageResource);

                    if ($response->successful()) {
                        $data = $response->json();
                        $predictionResult = $data['class'] ?? null;
                        $confidence = $data['confidence'] ?? null;
                    }
                } catch (Exception $e) {
                    Log::error('Flask API connection failed', ['error' => $e->getMessage()]);
                }
            }

            // Determine recyclability
            $is_recyclable = 0;
            if ($predictionResult === 'Recyclable' && $confidence >= 90) {
                $is_recyclable = 1;
            }

            // Find nearest team member
            $nearestTeam = User::where('role', 'team')
                ->selectRaw("id, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance", [
                    $request->latitude,
                    $request->longitude,
                    $request->latitude
                ])
                ->orderBy('distance', 'asc')
                ->first();

            // Create complaint
            $complaint = Complain::create([
                'user_id' => $request->user()->id,
                'complaint_type' => $request->complaint_type,
                'location' => $request->location,
                'description' => $request->description,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'is_recycleable' => $is_recyclable,
                'team_id' => $nearestTeam ? $nearestTeam->id : null,
                'image' => $imagePath,
                'status' => 'pending',
            ]);

            Log::info('Complaint submitted via API', [
                'user_id' => $request->user()->id,
                'complaint_id' => $complaint->id,
                'prediction' => $predictionResult,
                'confidence' => $confidence,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Complaint submitted successfully',
                'data' => [
                    'complaint_id' => $complaint->id,
                    'prediction' => $predictionResult ?? 'N/A',
                    'confidence' => $confidence ?? 0,
                    'is_recyclable' => $is_recyclable,
                    'status' => $complaint->status,
                    'team_assigned' => $nearestTeam ? true : false,
                ]
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit complaint',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all complaints for authenticated user
     */
    public function index(Request $request)
    {
        try {
            $complaints = Complain::where('user_id', $request->user()->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $complaints->map(function ($complaint) {
                    return [
                        'id' => $complaint->id,
                        'complaint_type' => $complaint->complaint_type,
                        'location' => $complaint->location,
                        'description' => $complaint->description,
                        'latitude' => $complaint->latitude,
                        'longitude' => $complaint->longitude,
                        'status' => $complaint->status,
                        'is_recycleable' => $complaint->is_recycleable,
                        'image' => $complaint->image ? url('storage/' . $complaint->image) : null,
                        'created_at' => $complaint->created_at->format('Y-m-d H:i:s'),
                    ];
                })
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch complaints',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single complaint details
     */
    public function show(Request $request, $id)
    {
        try {
            $complaint = Complain::where('user_id', $request->user()->id)
                ->where('id', $id)
                ->first();

            if (!$complaint) {
                return response()->json([
                    'success' => false,
                    'message' => 'Complaint not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $complaint->id,
                    'complaint_type' => $complaint->complaint_type,
                    'location' => $complaint->location,
                    'description' => $complaint->description,
                    'latitude' => $complaint->latitude,
                    'longitude' => $complaint->longitude,
                    'status' => $complaint->status,
                    'is_recycleable' => $complaint->is_recycleable,
                    'image' => $complaint->image ? url('storage/' . $complaint->image) : null,
                    'created_at' => $complaint->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $complaint->updated_at->format('Y-m-d H:i:s'),
                ]
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch complaint',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
