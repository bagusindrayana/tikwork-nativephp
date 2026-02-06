<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobController;

// Menu Routes (1 Menu 1 Controller)
// Menu Routes (All return the App Shell)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/explore', [HomeController::class, 'index'])->name('explore');
Route::get('/favorites', [HomeController::class, 'index'])->name('favorites');
Route::get('/profile', [HomeController::class, 'index'])->name('profile');

// API Routes for Feeds & Data
Route::get('/api/jobs', [HomeController::class, 'fetchJobs'])->name('api.jobs');
Route::get('/api/jobs/following', [HomeController::class, 'fetchFollowing'])->name('api.jobs.following');
Route::get('/api/jobs/explore', [ExploreController::class, 'fetchExplore'])->name('api.jobs.explore');
Route::get('/api/favorites', [FavoriteController::class, 'fetchFavorites'])->name('api.favorites');
Route::get('/api/profile', [ProfileController::class, 'fetchProfile'])->name('api.profile');

// Job Specific Routes
Route::get('/poster', [JobController::class, 'poster'])->name('poster.embed');
Route::get('/job/{id}', [JobController::class, 'viewJob'])->name('job.view');
Route::get('/open-external', [JobController::class, 'openExternal'])->name('open.external');

// Logic Action Routes
Route::post('/favorite/toggle', [FavoriteController::class, 'toggle'])->name('favorite.toggle');
Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
