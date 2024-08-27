<?php

use App\Http\Controllers\WeeklyDataController;
use App\Http\Controllers\WeeklyGoogleSheetsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/weekly-data', [WeeklyDataController::class, 'index'])->name('weekly.data');

Route::get('/export-to-sheets', [WeeklyGoogleSheetsController::class, 'exportToGoogleSheets']);
