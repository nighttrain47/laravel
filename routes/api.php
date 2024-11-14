<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;


// day la route de goi controller
Route::get('/books', [BookController::class, 'index']);
Route::get('/books/search', [BookController::class, 'search']);
