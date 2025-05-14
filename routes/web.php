<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('auth')->name('auth.')->group(function() {
    Route::get('/login', App\Livewire\Auth\Login\Main::class)->name('login');
    Route::get('/register', App\Livewire\Auth\Register\Main::class)->name('register');
});

Route::prefix('dashboard')->name('dashboard.')->group(function() {
    // Route::get('/', App\Livewire\App\Dashboard\Main\Main::class)->name('main');
    Route::get('/', App\Livewire\App\Home\Main::class)->name('main');
});

Route::prefix('inbox')->name('inbox.')->group(function() {
    Route::get('/', App\Livewire\App\Inbox\Main::class)->name('main');
});


// Route::get('/dashboard', App\Livewire\App\Dashboard\Main\Main::class)->name('dashboard');

Route::prefix('documents')->name('documents.')->group(function() {
    Route::get('/', App\Livewire\App\Dashboard\Documents\Main\Main::class)->name('main');
});
