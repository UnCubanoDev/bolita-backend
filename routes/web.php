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
Route::get('/download-apk', function () {
    $file = public_path('downloads/lottogame.apk');
    return response()->download($file, 'LottoGame.apk', [
        'Content-Type' => 'application/vnd.android.package-archive',
    ]);
})->name('download.apk');
