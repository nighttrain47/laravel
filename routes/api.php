<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;

// Authentication Routes
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);

// Optional: Password Reset Routes
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// Protected Routes (add middleware('auth') to existing routes that need protection)
Route::middleware(['auth'])->group(function () {
    Route::post('/editBook', [BookController::class, 'editBook']);
    Route::post('/addNewBook', [BookController::class, 'addNewBook']);
    Route::post('/addNewAuthor', [BookController::class, 'addNewAuthor']);
    Route::post('/addNewCategory', [BookController::class, 'addNewCategory']);
    Route::post('/deleteBookByID', [BookController::class, 'deleteBookByID']);
});

// Public Routes
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/books', [BookController::class, 'getAllBooks']);
Route::post('/getBookByID', [BookController::class, 'getBookByID']);