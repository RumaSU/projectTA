<?php

namespace App\Library;

use App\Library\Helper as LibHelper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

use App\Models\Log\UserActivity;
use App\Models\Users;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;


class UserHelper {
    public static function authUser($credentials) {
        
        $authUser = Auth::attempt($credentials);
        if (! $authUser) {
            Log::channel('user_log')->info(json_encode([
                'status' => false,
                'credentials' => $credentials,
                'session' => Session::all(),
                'statusMessage' => $authUser,
            ]));
            return (object) [
                'status' => false,
                'title' => 'Login Failed',
                'message' => 'We couldn’t log you in. Make sure your email and password are correct and try again.',
            ];
        }
        
        Log::channel('user_log')->info(json_encode([
            'status' => true,
            'credentials' => $credentials,
            'session' => Session::all(),
            'auth' => Auth::user(),
            'statusMessage' => $authUser,
        ]));
        
        return (object) [
            'status' => true,
            'title' => 'Welcome Back!',
            'message' => 'Welcome back! We’re getting things ready for you...',
        ];
    }
    
    public static function authUserByToken($token) {
        
    }
    
    public static function authByUuid($uuidUser) {
        // $authUser = Auth::attempt($credentials);
        // if (! $authUser) {
        //     Log::channel('user_log')->info(json_encode([
        //         'status' => false,
        //         'credentials' => $credentials,
        //         'session' => Session::all(),
        //         'statusMessage' => $authUser,
        //     ]));
        //     return (object) [
        //         'status' => false,
        //         'title' => 'Login Failed',
        //         'message' => 'We couldn’t log you in. Make sure your email and password are correct and try again.',
        //     ];
        // }
        
        // Log::channel('user_log')->info(json_encode([
        //     'status' => true,
        //     'credentials' => $credentials,
        //     'session' => Session::all(),
        //     'auth' => Auth::user(),
        //     'statusMessage' => $authUser,
        // ]));
        
        // return (object) [
        //     'status' => true,
        //     'title' => 'Welcome Back!',
        //     'message' => 'Welcome back! We’re getting things ready for you...',
        // ];
    }
    
    public static function createUsername($fullname, bool $letter = false) {
        $words = explode(' ', $fullname);
        $accronym = "";
        foreach($words as $word) {
            $accronym .= ucfirst(mb_substr($word, 0, 1));
        }
        
        $accronym = self::checkAccronym($accronym);
        
        $isUsernameUnique = false;
        $username = "";
        $countLoop = 1;
        $lengthLetter = 8;
        while(!$isUsernameUnique) {
            if ($countLoop > 10) {
                $countLoop = 0;
                $lengthLetter += 1;
            }
            
            $randomLetter = $letter ? LibHelper::randStr($lengthLetter, lower: false,) : mt_rand(1000, 9999) . mt_rand(1000, 9999);
            $username = $accronym . '-' . $randomLetter;
            if( ! (Users\User::where('username', '=' ,  $username )->exists() ) ) {
                $isUsernameUnique = true;
            }
            
            $countLoop++;
        }
        
        return $username;
    }
    
    
    
    // private function
    private static function checkAccronym($accronym) {
        $accLen = strlen($accronym);
        $minLen = 4;
        if ($accLen <= $minLen) {
            $accronym .= LibHelper::randStr($minLen - $accLen, lower: false, number: false);
        }
        
        return $accronym;
    }
    
    
}