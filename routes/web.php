<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Just for demo
Route::get('run-queue', function () {
    Artisan::call('queue:work --stop-when-empty --tries=2 --max-time=60');
    return true;
})->name('run-queue');
