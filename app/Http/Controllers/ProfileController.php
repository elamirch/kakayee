<?php

namespace App\Http\Controllers;

use App\Services\UserStateService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(protected UserStateService $userState) {}

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,'.$user->id,
            'gender' => 'nullable|string|in:male,female,other',
            'birth_date' => 'nullable|date',
            'profile_img_url' => 'nullable|string|max:2048',
            'major_id' => 'nullable|integer|exists:majors,id',
            'country_id' => 'nullable|integer|exists:countries,id',
            'province_id' => 'nullable|integer|exists:provinces,id',
            'city_id' => 'nullable|integer|exists:cities,id',
            'daily_goal' => 'nullable|integer|between:5,200',
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated',
            'user' => $this->userState->build($user),
        ]);
    }
}
