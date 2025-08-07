<?php

namespace App\Library;


use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

use App\Library\Helper as LibHelper;
use App\Models\Log\Chunks;


class ChunkHelper {
    protected static $disk = 'local';
    
    
    /**
     * Summary of getResumableRequest
     * @param \Illuminate\Http\Request $request
     * 
     * @return array|null
     */
    public static function getResumableRequest($request) {
        $mapResumable = [
            "resumableChunkNumber",
            "resumableChunkSize",
            "resumableCurrentChunkSize",
            "resumableTotalSize",
            "resumableType",
            "resumableIdentifier",
            "resumableFilename",
            "resumableRelativePath",
            "resumableTotalChunks",
        ];
        
        
        $col = collect($request->all())->filter(function($value, $key) use ($mapResumable) {
            // return str_starts_with($key, 'resumable');
            return in_array($key, $mapResumable);
        })->toArray();
        
        return count($col) == count($mapResumable) ? $col : null;
    }
    
    
    /**
     * Summary of getMetadataUploadFile
     * @param \Illuminate\Http\UploadedFile $file
     * @return array|null
     */
    public static function getMetadataUploadFile($file) {
        $realPath = LibHelper::normalizePath($file->getRealPath());
        $relativePath = str_replace(LibHelper::normalizePath(Storage::disk(self::$disk)->path('')), '', $realPath);
        
        return $file instanceof \Illuminate\Http\UploadedFile 
        ? [
            'originalName' => $file->getClientOriginalName(),
            'originalExt' => $file->getClientOriginalExtension(),
            'mime' => $file->getMimeType(),
            'size' => $file->getSize(),
            'realPath' => $realPath,
            'relativePath' => LibHelper::normalizePath($relativePath),
            'basename' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
        ] 
        : null;
    }
    
    
    /**
     * Summary of clearChunk
     * @param string $clienFileName
     * @return bool
     */
    public static function clearChunk($clienFileName) {
        return Storage::disk('local')->delete('chunks/' . $clienFileName);
    }
}