<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Display laravel logs
Route::get("/log", function () {
    $file = storage_path("logs/laravel.log");
    $content = file_get_contents($file);
    return nl2br($content);
});
