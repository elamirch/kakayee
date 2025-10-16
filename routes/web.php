<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\ImageUploadController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'index'])->middleware(['auth', 'verified'])->name('main');

Route::get('/lessons/edit', [LessonController::class, 'redirect_to_edit'])->middleware(['auth', 'verified']);
Route::resource('/lessons', LessonController::class)->middleware(['auth', 'verified']);
Route::get('/lessons/{lesson}/notes', [LessonController::class, 'notes'])->middleware(['auth', 'verified']);
Route::post('/lessons/{lesson}/check', [LessonController::class, 'check'])->middleware(['auth', 'verified']);
Route::post('/redirect_to_main', [LessonController::class, 'post_final_card_answer'])->middleware(['auth', 'verified']);

Route::get('/majors/edit', [MajorController::class, 'redirect_to_edit'])->middleware(['auth', 'verified']);
Route::resource('majors', MajorController::class)->middleware(['auth', 'verified']);

Route::get('/courses/edit', [CourseController::class, 'redirect_to_edit'])->middleware(['auth', 'verified']);
Route::resource('courses', CourseController::class)->middleware(['auth', 'verified']);

Route::get('/cards/edit', [CardController::class, 'redirect_to_edit'])->middleware(['auth', 'verified']);
Route::resource('cards', CardController::class)->middleware(['auth', 'verified']);

Route::get('/dashboard', function() {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get("/admin", function() {
    return view('admin.index');
})->middleware(['auth', 'verified'])->name('admin');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/upload-image', [ImageUploadController::class, 'store'])->name('upload.image');

require __DIR__.'/auth.php';
