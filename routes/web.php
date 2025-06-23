<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('auth')->name('auth.')->group(function() {
    // Route::prefix('/login')->group(function() {
    //     Route::prefix('/step')->group(function() {
    //         Route::get('', App\Livewire\Auth\Login\Main::class);
    //     });
    // });
    Route::get('/login', App\Livewire\Auth\Login\Main::class)->name('login');
    // Route::get('/login{step}', App\Livewire\Auth\Login\Main::class)->name('login');
    // Route::get('/login/{$step}', App\Livewire\Auth\Login\Main::class)->name('login');
    
    route::prefix('/register/lifecycle')->name('register.')->group(function() {
        route::prefix('/step')->name('step.')->group(function() {
            Route::get('/fullname', App\Livewire\Auth\Register\Form\Fullname::class)->name('fullname');
            Route::get('/birthdaygender', App\Livewire\Auth\Register\Form\BirthAndGender::class)->name('birth_gender'); 
            
        });
    });
    
    Route::get('/register', App\Livewire\Auth\Register\Main::class)->name('register');
});

Route::prefix('dashboard')->name('dashboard.')->group(function() {
    // Route::get('/', App\Livewire\App\Dashboard\Main\Main::class)->name('main');
    Route::get('/', App\Livewire\App\Home\Main::class)->name('home');
});

Route::prefix('inbox')->name('inbox.')->group(function() {
    Route::get('', App\Livewire\App\Inbox\Main::class)->name('main');
    // Route::get('#sent', App\Livewire\App\Inbox\Sent::class)->name('sent');
    // Route::get('#draft', App\Livewire\App\Inbox\Draft::class)->name('draft');
});

Route::prefix('mail')->name('mail.')->group(function() {
    Route::get('/', App\Livewire\App\Mail\Main::class)->name('main');
    // Route::get('/inbox', App\Livewire\App\Mail\Page\Inbox::class)->name('inbox');
});


// Route::get('/dashboard', App\Livewire\App\Dashboard\Main\Main::class)->name('dashboard');

Route::prefix('documents')->name('documents.')->group(function() {
    // Route::get('/', App\Livewire\App\Dashboard\Documents\Main\Main::class)->name('main');
    Route::get('/', App\Livewire\App\Documents\Main::class)->name('main');
});
