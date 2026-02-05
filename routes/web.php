<?php

use App\Http\Controllers\JobController;

Route::get('/', [JobController::class, 'index'])->name('home');
Route::get('/explore', [JobController::class, 'explore'])->name('explore');
Route::get('/favorites', [JobController::class, 'favorites'])->name('favorites');
Route::get('/profile', [JobController::class, 'profile'])->name('profile');

Route::get('/api/jobs', [JobController::class, 'fetchJobs'])->name('api.jobs');
Route::get('/api/jobs/following', [JobController::class, 'fetchFollowing'])->name('api.jobs.following');
Route::get('/api/jobs/explore', [JobController::class, 'fetchExplore'])->name('api.jobs.explore');
Route::get('/poster', [JobController::class, 'poster'])->name('poster.embed');
Route::get('/job/{id}', [JobController::class, 'viewJob'])->name('job.view');

// Backend Logic Routes
Route::post('/favorite/toggle', [JobController::class, 'toggleFavorite'])->name('favorite.toggle');
Route::post('/profile/update', [JobController::class, 'updateProfile'])->name('profile.update');

Route::get('/open-external', [JobController::class, 'openExternal'])->name('open.external');
