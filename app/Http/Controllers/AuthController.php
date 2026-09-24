<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\SendSMS;
use App\Services\UserStateService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct(protected UserStateService $userState) {}

    /**
     * Send an OTP code to the given phone number.
     *
     * @unauthenticated
     */
    public function sendotp(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required|regex:/^09\d{9}$/',
        ]);

        $otpCode = app()->environment('local', 'testing') ? 11111 : random_int(10000, 99999);

        $user = User::firstOrCreate(
            ['phone_number' => $validated['phone_number']],
            [
                'role' => 'user',
                'gems' => config('gamification.gems.starting_amount', 100),
            ]
        );

        $user->otp_code = (string) $otpCode;
        $user->otp_code_expiration = Carbon::now()->addMinutes(5);
        $user->save();

        $debugOtp = app()->environment('local', 'testing') ? $otpCode : null;

        if (! app()->environment('local', 'testing')) {
            $sendSMS = new SendSMS;
            $sendSMS->otp($validated['phone_number'], $otpCode);
        }

        return response()->json([
            'message' => 'OTP sent successfully',
            'debug_otp' => $debugOtp,
        ]);
    }

    /**
     * Verify the OTP and log the user in.
     *
     * @unauthenticated
     */
    public function authenticate(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required|regex:/^09\d{9}$/',
            'otp_code' => 'required|digits:5',
        ]);

        $user = User::where('phone_number', $validated['phone_number'])->first();

        if (! $user) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $debugOtp = app()->environment('local', 'testing') ? '11111' : null;
        $expected = $debugOtp ?? $user->otp_code;

        if (! $expected || ! hash_equals((string) $expected, (string) $validated['otp_code'])) {
            return response()->json(['error' => 'Invalid OTP'], 401);
        }

        if ($user->otp_code_expiration && $user->otp_code_expiration->lt(Carbon::now())) {
            return response()->json(['error' => 'OTP expired'], 401);
        }

        $user->otp_code = null;
        $user->otp_code_expiration = null;
        $user->save();

        $token = JWTAuth::claims([
            'last_logout' => $user->last_logout ? $user->last_logout->timestamp : 0,
        ])->fromUser($user);

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => $this->userState->build($user),
        ]);
    }

    /**
     * Refresh the current access token.
     *
     * @unauthenticated
     */
    public function refreshTokens()
    {
        try {
            $newToken = JWTAuth::claims([
                'expires_in' => config('jwt.ttl') * 60,
                'refresh_ttl' => config('jwt.refresh_ttl') * 60,
            ])->setToken(JWTAuth::getToken())->refresh();

            return response()->json([
                'access_token' => $newToken,
                'token_type' => 'bearer',
            ]);
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token expired, please login again'], 401);
        }
    }

    /**
     * Log out of the current device.
     */
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Logged out successfully']);
    }

    /**
     * Log out of all devices by bumping the last_logout timestamp.
     */
    public function logoutAllDevices()
    {
        $user = auth()->user();
        $user->last_logout = Carbon::now();
        $user->save();

        return response()->json(['message' => 'Logged out from all devices']);
    }

    /**
     * Get the authenticated user's full profile/state.
     */
    public function profile()
    {
        return response()->json($this->userState->build(auth()->user()));
    }
}
