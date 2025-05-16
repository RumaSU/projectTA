<?php

namespace App\Library;


use Illuminate\Support\Facades\Session;

use App\Models\Log\Chunks;


class ChunkHelper {
    public static function setSessionChunkLog($resumableFields, $keyChunk){
        $nowLog = [$resumableFields];
        
        if (!Session::has('log_chunk')) {
            Session::put('log_chunk', []);
        }
        
        $sessionLogListChunk = Session::get('log_chunk');
        if (!array_key_exists($keyChunk, $sessionLogListChunk)) {
            $sessionLogListChunk[$keyChunk] = $nowLog;
            
            Session::put('log_chunk', $sessionLogListChunk);
            return;
        }
        
        $logChunkById = $sessionLogListChunk[$keyChunk];
        $countLogChunk = count($logChunkById);
        
        $prevLog = $logChunkById[$countLogChunk - 1];
        $excludeKey = ['resumableChunkNumber', 'resumableCurrentChunkSize'];
        foreach ($resumableFields as $resKey => $resData) {
            if (in_array($resKey, $excludeKey)) {
                continue;
            }
            
            if ($resData != $prevLog[$resKey]) {
                $keySplit = preg_split('/(?=[A-Z])/', $resKey);
                $keyMessage = implode(' ', $keySplit);
                
                $errMessage = [
                    'errorReason' => $keyMessage . " doesn't equal as before",
                    'dataBefore' => $prevLog[$resKey],
                    'dataNow' => $resData
                ];
                
                $prevLog['error'] = $errMessage;
                $sessionLogListChunk[$keyChunk][] = $prevLog;
                Session::put('log_chunk', $sessionLogListChunk);
                
                return $errMessage;
            }
        }
        
        $sessionLogListChunk[$keyChunk][] = $resumableFields;
        Session::put('log_chunk', $sessionLogListChunk);
        
        return;
    }
    
    public static function getSessionChunkLog($keyChunk) {
        $sessionLogListChunk = Session::get('log_chunk');
        if (array_key_exists($keyChunk, $sessionLogListChunk)) {
            return $sessionLogListChunk[$keyChunk];
        }
        
        return;
    }
    
    public static function forgetSessionChunkLog($keyChunk) {
        $sessionLogListChunk = Session::get('log_chunk');
        unset($sessionLogListChunk[$keyChunk]);
        Session::put('log_chunk', $sessionLogListChunk);
        return;
    }
    
    // public function 
}