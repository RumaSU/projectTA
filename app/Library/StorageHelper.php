<?php

namespace App\Library;

use App\Library\Helper as LibHelper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

// use App\Models\Log\UserActivity;
// use App\Models\Users;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;


class StorageHelper {
    
    public static function checkFileExists($disk, $pathname) {
        return Storage::disk($disk)->exists($pathname);
    }
    
}