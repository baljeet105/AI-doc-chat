<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/upload', [DocumentController::class, 'upload']);
Route::post('/ask', [DocumentController::class, 'ask']);