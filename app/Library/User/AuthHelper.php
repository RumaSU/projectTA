<?php

namespace App\Library\User;

use App\Library\Helper as LibHelper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

use App\Models\Log\UserActivity;
use App\Models\User;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;


class AuthHelper {
    // public static $hostUrl = (Request::secure() ? 'https' : 'http') . '://' . Request::getHost() . (Request::getPort() ? ':' . Request::getPort() : '') . '/';
    public static function createUsername($fullname, bool $withLetter = false) {
        $words = explode(' ', $fullname);
        $accronym = "";
        foreach($words as $word) {
            $accronym .= ucfirst(mb_substr($word, 0, 1));
        }
        
        
        $isUsernameUnique = false;
        $username = "";
        while(!$isUsernameUnique) {
            $randomLetter = $withLetter ? LibHelper::randStrNum(6, false) : mt_rand(1000, 9999) . mt_rand(1000, 9999);
            $username = $accronym . $randomLetter;
            if( ! (User\User::where('username', '=' ,  $username )->exists() ) ) {
                $isUsernameUnique = true;
            }
        }
        
        return $username;
    }
}