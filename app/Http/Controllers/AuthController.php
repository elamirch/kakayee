<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Carbon\Carbon;
use App\Services\SendSMS;

class AuthController extends Controller
{
    /**
     * Send OTP
     * @unauthenticated
     */
    public function sendotp(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required|regex:/^09\d{9}$/',
        ]);

        $otp_code = env('APP_DEBUG') ? 11111 : rand(10000, 99999);

        $user = User::where('phone_number', $validated['phone_number'])->first();
        $user->otp_code = $otp_code;
        $user->save();

        if(env('APP_DEBUG')) {
            return response()->json([
                'message' => 'success: on debug mode, otp is 11111',
            ]);
        } else {
            $sendSMS = new SendSMS;
            $response = $sendSMS->otp($validated['phone_number'], $otp_code);

            return response()->json([
                'message' => 'success: OTP request sent',
                'API Response' => $response
            ]);
        }
    }

    /**
     * Logout user
    */
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Logged out successfully']);
    }
    
    /**
     * Get authenticated user profile
    */
    public function profile()
    {
        return response()->json(auth()->user());
    }

    /**
     * Refresh tokens
    */
    public function refreshTokens()
    {
        try {
            $newToken = JWTAuth::claims([
                'expires_in' => config('jwt.ttl') * 60,
                'refresh_ttl' => config('jwt.refresh_ttl') * 60
                ])->setToken(JWTAuth::getToken())
                ->refresh();
            return response()->json([
                'access_token' => $newToken,
                'token_type' => 'bearer'
            ]);
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token expired, please login again'], 401);
        }
    }
    
    /**
     * Log out of all devices
    */
    public function logoutAllDevices()
    {
        $user = JWTAuth::user();
    
        $user->last_logout = Carbon::now();
        $user->save();

        return response()->json(['message' => 'Logged out from all devices']);
    }

    /**
     * Get token info
    */
    public function tokenInfo() {
        return response()->json(['Token info' => JWTAuth::getPayload()]);
    }

    /**
     * Unified authentication endpoint.
     *
     * @unauthenticated
    */
    public function authenticate(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required|regex:/^09\d{9}$/',
            'otp_code'     => 'required|integer|digits:5',
        ]);

        $user = User::where('phone_number', $validated['phone_number'])->first();

        $otp_code = env('APP_DEBUG') ? 11111 : $user->otp_code;

        if ($user) {
            if (!isset($validated['otp_code']) ||
                $otp_code != (int) $validated['otp_code']) 
            {
                return response()->json(['error' => 'Invalid OTP'], 401);
            }

            $access_token = JWTAuth::claims([
                'expires_in' => config('jwt.ttl') * 60,
                'refresh_ttl' => config('jwt.refresh_ttl') * 60
                ])->fromUser($user);

            $user->otp_code = null;
            $user->save();

            return response()->json([
                'message'        => 'Login successful',
                'user'           => $user,
                'access_token' => $access_token,
            ]);
        }

        $user = User::create([
            'phone_number'  => $validated['phone_number'],
            'otp_code'      => $otp_code,
            'role'          => 'user',
        ]);
        $access_token = JWTAuth::claims([
                'expires_in' => config('jwt.ttl') * 60,
                'refresh_ttl' => config('jwt.refresh_ttl') * 60
                ])->fromUser($user);

        // OTP Logic shall be added
        return response()->json([
            'message' => 'User registered.',
            'user' => $user,
            'access_token' => $access_token,
        ], 201);
    }
}
