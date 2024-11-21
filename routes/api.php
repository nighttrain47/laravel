<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BookUpdateController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;

// Authentication Routes
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);

// Logout Route
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Optional: Password Reset Routes
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// test update book
Route::post('/updateBook/{id}', [BookUpdateController::class, 'updateBook']);

Route::middleware(['auth'])->group(function () {
    // Route::post('/updateBook/{id}', [BookUpdateController::class, 'updateBook']); // Changed to match controller method
    Route::post('/addNewBook', [BookUpdateController::class, 'addNewBook']);
    Route::post('/addNewAuthor', [BookUpdateController::class, 'addNewAuthor']);
    Route::post('/addNewCategory', [BookUpdateController::class, 'addNewCategory']);
    Route::post('/deleteBookByID', [BookUpdateController::class, 'deleteBookByID']);
});

// Public Routes
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/getAllBooks', [BookController::class, 'getAllBooks']);
Route::post('/getBookByID', [BookController::class, 'getBookByID']);
Route::get('/store_view', [BookController::class, 'store_view']);