<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Show laravel logs
Route::get("/log", function () {
    return response()->file(storage_path("logs/laravel.log"));
});
