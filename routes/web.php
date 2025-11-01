<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\AuthController;
use App\Http\Controllers\user\DashboardController;
use App\Http\Controllers\ComplainController;
use App\Http\Controllers\team\DashboardController as TeamDashboardController;
use App\Http\Controllers\team\LocationController;
use App\Http\Controllers\team\ComplainController as TeamComplainController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\TestNotification;
require __DIR__ . '/api.php';

Route::get('/signup', [AuthController::class, 'showSignUpForm'])->name('signUp.form');
Route::post('/signup', [AuthController::class, 'signUp'])->name('signUp.submit');
Route::get('/verify-otp', [AuthController::class, 'showOtpVerificationForm'])->name('auth.otpVerification');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('auth.verify.submit');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');


Route::middleware(['web.auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/complain/create', [ComplainController::class, 'showComplainForm'])->name('complain.create.form');
    Route::post('/complain/store', [ComplainController::class, 'storeComplain'])->name('complain.store');
});

Route::middleware(['web.auth'])->group(function () {

    
    Route::get('/team/dashboard', [TeamDashboardController::class, 'index'])->name('team.dashboard');
    Route::post('/team/update-location', [LocationController::class, 'updateLocation'])->name('team.update.location');
    Route::get('/team/complaints', [TeamComplainController::class, 'showAssignedComplaints'])->name('team.complaints');
    Route::post('/team/complaint/{id}/update-status', [TeamComplainController::class, 'updateStatus'])->name('team.complaint.updateStatus');
});


