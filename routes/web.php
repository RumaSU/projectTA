<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['guest'])->prefix('auth')->name('auth.')->group(function() {
    // redirect default route
    Route::redirect('/', 'auth/login');
    Route::get('/login', App\Livewire\Auth\Login\Main::class)->name('login');
    
    Route::get('/register', App\Livewire\Auth\Register\Main::class)->name('register');
    Route::prefix('/register/lifecycle')->name('register.')->group(function() {
        // redirect default route
        Route::get('/', function() { 
            $defaultRedirect = route('auth.register');
            if (session()->has('register_step_initialized')) $defaultRedirect = route('auth.register.step.basic_info');
            return redirect($defaultRedirect);
        });
        
        Route::prefix('/step')->name('step.')->group(function() {
            // redirect default route
            Route::get('/', function() {
                $defaultRedirect = route('auth.register');
                if (session()->has('register_step_initialized')) $defaultRedirect = route('auth.register.step.basic_info');
                return redirect($defaultRedirect);
            });
            
            Route::get('/basic_info', App\Livewire\Auth\Register\Form\BasicInformation::class)->name('basic_info');
            Route::get('/credentials', App\Livewire\Auth\Register\Form\Credentials::class)->name('credentials');
        });
        
    });
    
});

Route::middleware(['auth'])->name('app.')->group(function() {
// Route::name('app.')->group(function() {
    
    Route::prefix('dashboard')->name('dashboard.')->group(function() {
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
     
    Route::get('/logout', function() {
        Auth::logout();
        
        return redirect('/auth');
    });
});

Route::prefix('/session')->group(function() {
    
    Route::get('get-all', function() {
        dump(session()->all());
    });
    
    Route::get('flush', function() {
        dump('session flush');
        session()->flush();
        dump(session()->all());
    });
});
