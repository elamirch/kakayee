<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        return User::all();
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        $data = $request->validate([
            'phone_number' => 'required|string|unique:users,phone_number',
            'first_name'   => 'nullable|string',
            'last_name'    => 'nullable|string',
            'email'        => 'nullable|email|unique:users,email',
            'height'       => 'nullable|integer',
            'weight'       => 'nullable|integer',
            'ideal_weight' => 'nullable|integer',
            'BMI'          => 'nullable|numeric',
            'daily_calories' => 'nullable|integer',
            'gender'       => 'nullable|string',
            'blood_type'   => 'nullable|string',
            'age'          => 'nullable|integer',
            'profile_img_url' => 'nullable|string|unique:users,profile_img_url',
            'otp_code'     => 'nullable|integer',
            'otp_code_expiration' => 'nullable|date',
            'refresh_token' => 'nullable|string',
            'birth_date'    => 'nullable|date',
            'role'          => 'nullable|string|in:user,admin',
        ]);

        $user = User::create($data);

        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        if (auth()->id() !== $user->id && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        return $user;
    }

    public function update(Request $request, User $user)
    {
        if (auth()->id() !== $user->id && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        $data = $request->validate([
            'phone_number' => 'nullable|string|unique:users,phone_number,' . $user->id,
            'first_name'   => 'nullable|string',
            'last_name'    => 'nullable|string',
            'email'        => 'nullable|email|unique:users,email,' . $user->id,
            'height'       => 'nullable|integer',
            'weight'       => 'nullable|integer',
            'ideal_weight' => 'nullable|integer',
            'BMI'          => 'nullable|numeric',
            'daily_calories' => 'nullable|integer',
            'gender'       => 'nullable|string',
            'blood_type'   => 'nullable|string',
            'age'          => 'nullable|integer',
            'profile_img_url' => 'nullable|string|unique:users,profile_img_url,' . $user->id,
            'birth_date'    => 'nullable|date',
        ]);

        $user->update($data);

        return $user;
    }

    public function destroy(User $user)
    {
        if (auth()->id() !== $user->id && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        $user->delete();

        return response()->json(null, 204);
    }
}
