<?php

namespace App\Library\Documents;


use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use App\Library\Helper as LibHelper;
use App\Library\FileHelper;
use App\Library\ChunkHelper;

use App\Jobs\Documents\CreateNew;

use App\Models\Documents;
use App\Models\Files as FilesMod;


use Ramsey\Uuid\Uuid;

class Helper {
    
    public static $accept;
    public static $limit;
    protected static $config;
    
    private static function loadConfig() {
        self::$config = config('custom_upload.documents');
    }
    
    
    /**
     * Summary of saveNewDocuments
     * @param \Illuminate\Http\UploadedFile $file
     * @param \Illuminate\Http\Request $request
     * @param array|object $config
     * 
     * @return object
     */
    public static function saveNewDocuments($file, $request) {
        self::loadConfig();
        try {
            
            if (! $file instanceof \Illuminate\Http\UploadedFile) {
                throw new Exception('File is not a valid uploaded file.');
            }
            Log::channel('user_log')->info('Helper save new documents success check file instance of');
            
            if (! in_array($file->getMimeType(), self::$config['accept'])) {
                Log::channel('user_log')->info('Helper save new documents error check mime type', ['config', self::$config]);
                
                throw new Exception('Invalid file type.');
            }
            Log::channel('user_log')->info('Helper save new documents success check file mime type', ['config', self::$config]);
            
            if ( $file->getSize() > self::$config['limit'] ) {
                throw new Exception('');
            }
            Log::channel('user_log')->info('Helper save new documents success check file size');
            
            
            $fileMetadata = ChunkHelper::getMetadataUploadFile($file);
            $requestData = collect($request->all())
                ->filter(fn($value) => !($value instanceof \Illuminate\Http\UploadedFile))
                ->toArray();
            $ownerId = Auth::user()->id_user; 
            $sessionId = session()->getId();
            Log::channel('user_log')->info('Helper save new documents request data', ['request' => $requestData]);
            
            CreateNew::dispatch($fileMetadata, $requestData, $ownerId, $sessionId);
            
            return (object) [
                'status' => true,
                'message' => 'process new documents',
            ];
            
        } catch (Exception $e) {
            
            Log::channel('user_log')->info('error save new document', ['message' => $e->getMessage()]);
            
            return (object) [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
    
    
    
    
    public static function rollbackDocuments($id_document) {
        
    }
    
    
    private static function cleanChunkStorage($file) {
        $isChunkExists = Storage::disk('local')->exists('chunks/' . $file->getFilename());
        if (!$isChunkExists) {
            return (object) array(
                'status' => false,
                'message' => 'Chunk not found'
            );
        }
        
        Storage::disk('local')->delete('chunks/' . $file->getFilename());
        return (object) array(
            'status' => true,
            'message' => 'Chunk deleted successfully'
        );
    }
}