<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    config(['sanctum.middleware' => []]);
    return view('welcome');
})->name('home');

Route::get('/privacy', function () {
    config(['sanctum.middleware' => []]);
    return view('privacy');
})->name('privacy');

Route::get('/faq', function () {
    config(['sanctum.middleware' => []]);
    return view('faq');
})->name('faq');

Route::get('/download-apk', function() {
    $path = resource_path('app/Lotto Game.apk');
    return response()->download($path, 'LottoGame.apk');
})->name('download.apk');
