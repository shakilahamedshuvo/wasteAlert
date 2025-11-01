<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Otp;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;

class AuthController extends Controller
{
    public function showSignUpForm()
    {
        return view('auth.register');
    }

    /**
     * Handle the sign-up form submission.
     */
    public function signUp(Request $request)
    {
        Log::info('Starting signUp process', [
            'timestamp' => now()->toDateTimeString(),
            'input' => $request->except('password', 'password_confirmation')
        ]);

        try {
            // Validate user input
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'required|string|max:15',
                'password' => 'required|string|min:8',
            ]);
            Log::info('Validation passed', ['email' => $validatedData['email']]);
        } catch (Exception $e) {
            Log::error('Validation failed', ['error' => $e->getMessage()]);
            return back()->withErrors($e->getMessage())->withInput();
        }

        try {
            // Create the user
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'password' => bcrypt($validatedData['password']),
                'role' => "user"
            ]);
            Auth::login($user); // Log the user in after registration
            Log::info('User created', ['user_id' => $user->id, 'email' => $user->email]);
            
        } catch (Exception $e) {
            Log::error('User creation failed', ['error' => $e->getMessage()]);
            return back()->withErrors('User creation failed.')->withInput();
        }

        try {
            // Generate OTP
            $otp = rand(100000, 999999);
            $otpExpiry = now()->addMinutes(10);
            Log::info('OTP generated', ['otp' => $otp, 'expires_at' => $otpExpiry]);

            // Store OTP
            Otp::create([
                'user_id' => $user->id,
                'otp_code' => $otp,
                'expires_at' => $otpExpiry,
            ]);
            Log::info('OTP saved to database', ['user_id' => $user->id]);
        } catch (Exception $e) {
            Log::error('OTP generation or save failed', ['error' => $e->getMessage()]);
            return back()->withErrors('Failed to generate OTP.')->withInput();
        }

        try {
            // Send OTP mail
            Mail::to($user->email)->send(new SendOtpMail($otp));
        } catch (Exception $e) {
            Log::error('OTP Mail sending failed', [
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Mail sending failed. Check logs for details']);
        }

        Log::info('SignUp process completed successfully', ['user_id' => $user->id]);

        return redirect()->route('auth.otpVerification')->with('success', 'OTP sent to your email.');
    }
    public function showOtpVerificationForm()
    {
        $user = auth()->user();
        Log::info('Showing OTP verification form', ['user_id' => $user ? $user->id : null]);
        if(!$user) {
            Log::warning('Unauthorized access to OTP verification form');
            return redirect()->route('signUp.form')->with('error', 'You need to login first.');
        }
        return view('auth.otpVerification');
    }
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = auth()->user();
        if (!$user) {
            return redirect()->route('signUp.form')->with('error', 'You need to login first.');
        }

        $otpRecord = Otp::where('user_id', $user->id)
                        ->where('otp_code', $request->otp)
                        // ->where('expires_at', '>', now())
                        ->first();

        if ($otpRecord) {
            // OTP is valid
            
            Log::info('OTP verified successfully', ['user_id' => $user->id]);
            $user->is_verified = 1;
            $user->save();
            return redirect()->route('user.dashboard')->with('success', 'OTP verified successfully.');
        } else {
            // OTP is invalid
            Log::warning('OTP verification failed', ['user_id' => $user->id]);
            return back()->withErrors('Invalid or expired OTP. Please try again.');
        }
    }
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request)
{
    // 1️⃣ Validate user input
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);
    Log::info('Validation passed', ['email' => $request->email]);

    // 2️⃣ Get credentials
    $credentials = $request->only('email', 'password');
    Log::info('Credentials captured', ['email' => $credentials['email']]);

    // 3️⃣ Attempt authentication
    if (Auth::attempt($credentials)) {
        Log::info('Auth::attempt passed', ['email' => $credentials['email']]);

        // 4️⃣ Regenerate session
        $request->session()->regenerate();
        Log::info('Session regenerated', ['session_id' => session()->getId()]);

        // 5️⃣ Redirect to intended page
        Log::info('Login successful, redirecting to dashboard', ['email' => $credentials['email']]);
        if(Auth::user()->getRole() == 'team') {
            return redirect()->intended('team/dashboard');
        }
        return redirect()->intended('dashboard');
    }

    // 6️⃣ If authentication fails
    Log::warning('Login attempt failed', ['email' => $credentials['email']]);
    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
}

}
