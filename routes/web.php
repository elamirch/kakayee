<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [MainController::class, 'index'])->middleware(['auth', 'verified'])->name('main');
Route::post('/show', [MainController::class, 'show'])->middleware(['auth', 'verified'])->name('show');

Route::get('/dashboard', [CardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get("/admin/cards", [AdminController::class, 'cards'])->middleware(['auth', 'verified'])->name('cards');
Route::post("/admin/cards", [AdminController::class, 'cards'])->middleware(['auth', 'verified'])->name('get_cards');

Route::get("/admin/lessons", [AdminController::class, 'lessons'])->middleware(['auth', 'verified'])->name('lessons');
Route::post("/admin/lessons", [AdminController::class, 'lessons'])->middleware(['auth', 'verified'])->name('get_lessons');

Route::get("/admin/chapters", [AdminController::class, 'chapters'])->middleware(['auth', 'verified'])->name('chapters');
Route::post("/admin/chapters", [AdminController::class, 'chapters'])->middleware(['auth', 'verified'])->name('get_chapters');

Route::get("/admin/courses", [AdminController::class, 'courses'])->middleware(['auth', 'verified'])->name('courses');
Route::post("/admin/courses", [AdminController::class, 'courses'])->middleware(['auth', 'verified'])->name('get_courses');

Route::get("/admin/majors", [AdminController::class, 'majors'])->middleware(['auth', 'verified'])->name('majors');
Route::post("/admin/majors", [AdminController::class, 'majors'])->middleware(['auth', 'verified'])->name('get_majors');

Route::get("/admin", function() {
    return view('admin');
})->middleware(['auth', 'verified'])->name('admin');




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
