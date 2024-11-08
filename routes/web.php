<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\DataExportController;

Route::get('/export-all-data', [DataExportController::class, 'exportAllData']);
