<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\webapp\AuthApiController;
use App\Http\Controllers\webapp\ComplainApiController;

/*
|--------------------------------------------------------------------------
| API Routes for Mobile App
|--------------------------------------------------------------------------
*/

// Public routes
Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthApiController::class, 'register']);
    Route::post('/verify-otp', [AuthApiController::class, 'verifyOtp']);
    Route::post('/login', [AuthApiController::class, 'login']);
});

// Protected routes (require authentication)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/profile', [AuthApiController::class, 'profile']);
    
    // Team locations
    Route::get('/team-locations', [AuthApiController::class, 'getTeamLocations']);
    
    // Complaints
    Route::get('/complaints', [ComplainApiController::class, 'index']);
    Route::post('/complaints', [ComplainApiController::class, 'store']);
    Route::get('/complaints/{id}', [ComplainApiController::class, 'show']);
});

Route::get('/', function () {
    return view('index');
}); 
