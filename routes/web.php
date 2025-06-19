<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ImageController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


//Route::get('/', function () {
//    return Inertia::render('Index', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
//});

Route::get('/', [IndexController::class, 'Index'])->name('index.index');

Route::get('/about', function () {
    return Inertia::render('About');
});

Route::get('/journal/', [JournalController::class, 'index'])->name('journal.index');
Route::get('/journal/create', [JournalController::class, 'create'])->name('journal.create');
Route::post('/journal/store', [JournalController::class, 'store'])->name('journal.store');
Route::get('/journal/{id}', [JournalController::class, 'show'])->name('journal.show');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/albums/', [ImageController::class, 'getAllByAlbum'])->name('image.getAllByAlbum');
Route::get('/albums/create', [AlbumController::class, 'create'])->name('album.create');
Route::post('/albums/store', [AlbumController::class, 'store'])->name('album.store');

Route::get('/image/', [ImageController::class, 'index'])->name('image.index');
Route::get('/image/create', [ImageController::class, 'create'])->name('image.create');
Route::get('/albums/{album_id}/image', [ImageController::class, 'getByAlbum'])->name('image.getByAlbum');
Route::post('/image/store', [ImageController::class, 'store'])->name('image.store');

require __DIR__.'/auth.php';
