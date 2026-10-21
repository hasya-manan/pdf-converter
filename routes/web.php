<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConversionController;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/convert', [ConversionController::class, 'convert']);
Route::get('/download/{filename}', [ConversionController::class, 'download']);
