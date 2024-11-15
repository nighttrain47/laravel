<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\DataExportController;

Route::get('/export-all-data', [DataExportController::class, 'exportAllData']);

use App\Http\Controllers\SearchController;

Route::get('/search', [SearchController::class, 'search'])->name('search');
