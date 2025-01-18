<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Show the login form
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');

// Handle the login submission
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Logout route
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
