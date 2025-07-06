<?php

namespace App\Library\User;

use App\Library\Helper as LibHelper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

use App\Models\Log\UserActivity;
use App\Models\Users;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;


class Helper {
    public static function checkUser($id) {
        return Users\User::find($id)->exists();
    }
    
    
}