<?php

namespace App\Library\User;


use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use App\Models\Log\UserActivity;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;


class Activity {
    // public static $hostUrl = (Request::secure() ? 'https' : 'http') . '://' . Request::getHost() . (Request::getPort() ? ':' . Request::getPort() : '') . '/';
    public static function createActivity($id_user, array $activity_type, array $action) {
        $carbonNow = Carbon::now();
        UserActivity::create([
            'id' => Str::uuid(),
            'id_user' => $id_user,
            'activity_type' => json_encode($activity_type),
            'action' => json_encode($action),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'created_at' => $carbonNow,
        ]);
    }
}