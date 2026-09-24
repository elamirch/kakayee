<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\GamificationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\LessonSessionController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// --- Public ---
Route::post('auth/send-otp', [AuthController::class, 'sendotp']);
Route::post('auth/authenticate', [AuthController::class, 'authenticate']);
Route::post('auth/refresh', [AuthController::class, 'refreshTokens']);

Route::middleware(['auth:api', 'check_last_logout'])->group(function () {

    // --- Auth / Profile ---
    Route::get('auth/profile', [AuthController::class, 'profile']);
    Route::patch('auth/profile', [ProfileController::class, 'update']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('auth/logout-all', [AuthController::class, 'logoutAllDevices']);

    // --- Images ---
    Route::post('images', [ImageUploadController::class, 'store']);

    // --- Users ---
    Route::apiResource('users', UserController::class)->except(['store']);

    // --- Home ---
    Route::get('home', [HomeController::class, 'index']);

    // --- Locations ---
    Route::get('locations', [LocationController::class, 'index']);

    // --- Content ---
    Route::get('majors', [MajorController::class, 'index']);
    Route::get('majors/{major}', [MajorController::class, 'show']);

    Route::get('courses', [CourseController::class, 'index']);
    Route::get('courses/{course}', [CourseController::class, 'show']);
    Route::post('courses/select', [CourseController::class, 'select']);

    Route::get('lessons', [LessonController::class, 'index']);
    Route::get('lessons/{lesson}', [LessonController::class, 'show']);

    // --- Learning sessions ---
    Route::post('lessons/{lesson}/start', [LessonSessionController::class, 'start']);
    Route::post('lessons/{lesson}/practice', [LessonSessionController::class, 'practice']);
    Route::post('lessons/{lesson}/practice-complete', [LessonSessionController::class, 'completePractice']);
    Route::post('cards/{card}/answer', [LessonSessionController::class, 'answer']);

    // --- Gamification ---
    Route::get('hearts', [GamificationController::class, 'hearts']);
    Route::post('hearts/refill', [GamificationController::class, 'refillHearts']);
    Route::post('streak-freeze/buy', [GamificationController::class, 'buyStreakFreeze']);

    // --- Quests & achievements ---
    Route::get('quests', [QuestController::class, 'index']);
    Route::post('quests/{userQuest}/claim', [QuestController::class, 'claim']);
    Route::get('achievements', [AchievementController::class, 'index']);

    // --- Leaderboard ---
    Route::get('leaderboard', [LeaderboardController::class, 'index']);

    // --- Admin content management ---
    Route::prefix('admin')->middleware('is_admin')->group(function () {
        Route::post('majors', [\App\Http\Controllers\Admin\MajorController::class, 'store']);
        Route::put('majors/{major}', [\App\Http\Controllers\Admin\MajorController::class, 'update']);
        Route::delete('majors/{major}', [\App\Http\Controllers\Admin\MajorController::class, 'destroy']);

        Route::post('courses', [\App\Http\Controllers\Admin\CourseController::class, 'store']);
        Route::put('courses/{course}', [\App\Http\Controllers\Admin\CourseController::class, 'update']);
        Route::delete('courses/{course}', [\App\Http\Controllers\Admin\CourseController::class, 'destroy']);

        Route::post('lessons', [\App\Http\Controllers\Admin\LessonController::class, 'store']);
        Route::put('lessons/{lesson}', [\App\Http\Controllers\Admin\LessonController::class, 'update']);
        Route::delete('lessons/{lesson}', [\App\Http\Controllers\Admin\LessonController::class, 'destroy']);

        Route::post('cards', [\App\Http\Controllers\Admin\CardController::class, 'store']);
        Route::put('cards/{card}', [\App\Http\Controllers\Admin\CardController::class, 'update']);
        Route::delete('cards/{card}', [\App\Http\Controllers\Admin\CardController::class, 'destroy']);

        Route::post('sources', [\App\Http\Controllers\Admin\SourceController::class, 'store']);
        Route::put('sources/{source}', [\App\Http\Controllers\Admin\SourceController::class, 'update']);
        Route::delete('sources/{source}', [\App\Http\Controllers\Admin\SourceController::class, 'destroy']);
    });
});
