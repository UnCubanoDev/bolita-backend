<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    config(['sanctum.middleware' => []]);
    return view('welcome');
})->name('home');
