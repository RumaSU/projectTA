<?php

namespace App\Http\Controllers\FilesController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Handler\ResumableJSUploadHandler;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Storage\ChunkStorage;
use Pion\Laravel\ChunkUpload\Config\AbstractConfig;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

use App\Services\Documents\UploadService as DocumentUpload;

use App\Utils\RequestUtils;

class Uploads extends Controller
{
    private const MAP_TYPE_UPLOAD = ['documents'];
    
    public function upload($type, Request $request): JsonResponse {
        
        if (! in_array($type, static::MAP_TYPE_UPLOAD)) { 
            return RequestUtils::createJsonResponse([
                'status' => 'error',
                'message' => 'Invalid upload type. Please check the allowed file types.'
            ], 400);
        }
        
        if ($request->isMethod('get')) {
            return $this->uploadTest($request);
        }
        
        if (! $request->hasFile('file')) {
            return RequestUtils::createJsonResponse([
                'status' => 'error',
                'message' => 'No file uploaded. Please ensure you select a file.'
            ], 400);
        }
        
        return $this->uploadFiles($type, $request);
        
    }
    
    private function uploadTest(Request $request) {
        $filename = $request->input('resumableFilename');
        $identifier = $request->input('resumableIdentifier');
        $chunkNumber = $request->input('resumableChunkNumber');
        $sessionId = session()->getId();
        
        if (empty($filename) || empty($identifier) || empty($chunkNumber)) {
            return RequestUtils::createJsonResponse([
                'status' => 'error',
                'message' => 'Incomplete resumable parameters.'
            ], 400);
        }
        
        $chunkFileName = "{$filename}-{$sessionId}-{$identifier}.{$chunkNumber}.part";
        $safePath = str_replace(['..', '\\', '//'], '', $chunkFileName);
        
        $chunkPath = config('chunk-upload.storage.chunks') . "/{$safePath}";
        
        $exists = Storage::disk(config('chunk-upload.storage.disk'))->exists($chunkPath);
        
        return $exists 
            ? RequestUtils::createJsonResponse([
                'status' => 'chunk_exists',
                'message' => 'Chunk already exists on the server.'
            ], 200)
            : RequestUtils::createJsonResponse([
                'status' => 'chunk_not_found',
                'message' => 'Chunk not found on the server.'
            ], 404);
    }
    
    
    private function uploadFiles($type, Request $request) {
        
        switch($type) {
            case 'documents':
                return DocumentUpload::handle($request);
            default:
                return RequestUtils::createJsonResponse([
                    'status' => 'error',
                    'message' => 'Upload type not supported by the system.'
                ], 400);
        }
        
    }
    
}
