<?php
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookUpdateController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;

// API Routes group with middleware
Route::middleware('api')->group(function () {
    
    // Public Routes (No auth required)
    Route::get('/search', [SearchController::class, 'search']);
    Route::get('/getAllBooks', [BookController::class, 'getAllBooks']);
    Route::get('/getBookByID/{id}', [BookController::class, 'getBookByID']);

    // Authentication Routes (No auth required)
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);

    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/updateBook/{id}', [BookUpdateController::class, 'updateBook']);
        Route::post('/addNewBook', [BookUpdateController::class, 'addNewBook']);
        Route::post('/addNewAuthor', [BookUpdateController::class, 'addNewAuthor']);
        Route::post('/addNewCategory', [BookUpdateController::class, 'addNewCategory']);
        Route::post('/deleteBookByID', [BookUpdateController::class, 'deleteBookByID']);
    });
});