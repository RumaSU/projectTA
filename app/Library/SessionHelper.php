<?php

namespace App\Library;

use App\Library\Helper as LibHelper;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Ramsey\Uuid\Uuid;


class SessionHelper {
    
    public static function createSession($nameSession) {
        $idSession = LibHelper::generateUniqueUuId();
        Session::put($nameSession, ['id' => $idSession]);
    }
    
    public static function checkSessionExists($nameSession) {
        return Session::exists($nameSession);
    }
    
    public static function forgetSession($nameSession) {
        Session::forget($nameSession);
    }
    
    public static function bulkForgetSession($bulkNameSession) {
        foreach($bulkNameSession as $val) {
            self::forgetSession($val);
        }
    }
    
    public static function forgetRelatedSession($prefix) {
        foreach (Session::all() as $key => $val) {
            if (Str::startsWith($key, $prefix)) {
                self::forgetSession($key);
            }
        }
    }
    
    public static function UpdateSession($nameSession, $key, $value) {
        if (! self::checkSessionExists($nameSession)) {
            self::createSession($nameSession);
        }
        
        $sessionGet = self::getSession($nameSession);
        $sessionGet[$key] = array_merge($sessionGet[$key] ?? [], $value);
        
        self::putSession($nameSession, $sessionGet);
        self::updateIdSession($nameSession);
    }
    
    public static function updateIdSession($nameSession) {
        $newUuid = LibHelper::generateUniqueUuId();
        
        $sessionGet = self::getSession($nameSession);
        $sessionGet['id'] = $newUuid;
        
        self::putSession($nameSession, $sessionGet);
    }
    
    public static function getSession($nameSession, $default = null) {
        return Session::get($nameSession, $default);
    }
    
    public static function putSession($nameSession, $value) {
        Session::put($nameSession, $value);
    }
    
}