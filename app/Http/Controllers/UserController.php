<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserStateService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected UserStateService $userState) {}

    public function index()
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        return response()->json([
            'users' => User::query()
                ->with('major', 'country', 'province', 'city')
                ->orderBy('id')
                ->get()
                ->map(fn ($user) => $this->userState->build($user)),
        ]);
    }

    public function show(User $user)
    {
        if (! auth()->user()->isAdmin() && auth()->id() !== $user->id) {
            abort(403);
        }

        return response()->json(['user' => $this->userState->build($user)]);
    }

    public function update(Request $request, User $user)
    {
        if (! auth()->user()->isAdmin() && auth()->id() !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'phone_number' => 'sometimes|regex:/^09\d{9}$/|unique:users,phone_number,'.$user->id,
            'email' => 'sometimes|nullable|email|unique:users,email,'.$user->id,
            'name' => 'sometimes|nullable|string|max:255',
            'first_name' => 'sometimes|nullable|string|max:255',
            'last_name' => 'sometimes|nullable|string|max:255',
            'role' => 'sometimes|string|in:user,admin',
            'profile_img_url' => 'sometimes|nullable|string|max:2048',
            'major_id' => 'sometimes|nullable|integer|exists:majors,id',
            'country_id' => 'sometimes|nullable|integer|exists:countries,id',
            'province_id' => 'sometimes|nullable|integer|exists:provinces,id',
            'city_id' => 'sometimes|nullable|integer|exists:cities,id',
            'xp' => 'sometimes|nullable|integer|min:0',
            'gems' => 'sometimes|nullable|integer|min:0',
            'heart' => 'sometimes|nullable|integer|min:0',
            'daily_goal' => 'sometimes|nullable|integer|between:5,200',
        ]);

        $user->update($validated);

        return response()->json(['user' => $this->userState->build($user)]);
    }

    public function destroy(User $user)
    {
        if (! auth()->user()->isAdmin() && auth()->id() !== $user->id) {
            abort(403);
        }
        $user->delete();

        return response()->json(null, 204);
    }
}
