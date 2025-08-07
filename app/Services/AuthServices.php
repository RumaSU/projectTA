<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

use App\Utils\LogUtils;
use App\Enums\AuthField;

class AuthServices {
    
    public function auth(string $field, string $password) {
        
        $credentials = [
            AuthField::detect($field)->value => $field,
            'password' => $password
        ];
        
        $status = Auth::attempt($credentials); 
        LogUtils::log(
            'user_log',
            $status 
                ? 'Success'
                : 'Failed',
            [
                'status' => $status,
                'credentials' => hash('sha256', json_encode($credentials)),
                'session' => session()->all(),
                'user' => Auth::user(),
            ]
        );
        
        return (object) [
            'status' => $status,
            'credentials' => $credentials,
            'title' => $status ? 'Welcome Back!' : 'Login Failed',
            'message' => $status
                ? 'Welcome back! We’re getting things ready for you...'
                : 'We couldn’t log you in. Make sure your email and password are correct and try again.',
        ];
    }
    
    public function auth_token(string $token) {
        // untuk auth berdasarkan token tapi nanti akan dibuat
    }
}