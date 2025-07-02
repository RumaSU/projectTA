<?php

namespace App\Library;

use App\Library\Helper as LibHelper;
use App\Library\StorageHelper;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

// use App\Models\Log\UserActivity;
use App\Models\Signatures;
use App\Models\Files as ModFiles;

class SignatureHelper {
    
    private static $acceptMimes = ['svg+xml', 'png'];
    
    public static function saveSignatures($value, $uuidSignature) {
        // $uuidSignatureData;
        
        try {
            $uuidSignatureType = LibHelper::generateUniqueUuId('v4', 'id_signature_type', Signatures\SignatureType::class);
            $saveSignatureType = Signatures\SignatureType::create([
                'id_signature_type' => $uuidSignatureType,
                'id_signature' => $uuidSignature,
                'type' => $value->key,
            ]);
            if (! $saveSignatureType) {
                self::rollbackSignatures($uuidSignature);
                throw new \Exception("Failed to save signature type '{$value->key}' into the signature group.");
            }
            
            
            $uuidSignatureData = LibHelper::generateUniqueUuId('v7', 'id_signature_data', Signatures\SignatureData::class);
            
            $saveSignatureData = Signatures\SignatureData::create([
                'id_signature_data' => $uuidSignatureData,
                'id_signature' => $uuidSignature,
                'pad_base64' => json_encode($value->pad_images),
                'pad_points' => json_encode($value->pad_json),
            ]);
            
            if (! $saveSignatureData) {
                self::rollbackSignatures($uuidSignature);
                throw new \Exception('Failed to save drawing data for the signature.');
            }
            
            foreach($value->pad_images as $base_image) {
                $saveResult = self::savePadBase64Image($base_image, $value->key);
                
                if (! $saveResult->status) {
                    throw new \Exception("Failed to save base64 image for '{$value->key}': {$saveResult->message}");
                }
                
                $uuidSignatureFile = LibHelper::generateUniqueUuId('v7', 'id_signature_file', Signatures\SignatureFile::class);
                $saveSignatureFile = Signatures\SignatureFile::create([
                    'id_signature_file' => $uuidSignatureFile,
                    'id_signature_type' => $uuidSignatureType,
                    'id_file_signature' => $saveResult->file_info->id_file_signature,
                ]);
                
                if (! $saveSignatureFile) {
                    self::rollbackSignatures($uuidSignature);
                    throw new \Exception("Failed to create file reference record for '{$value->key}'.");
                }
            }
            
            
            
            return (object) [
                'status' => true,
                'message' => 'Signature saved successfully.',
                'data' => [
                    'id_signature' => $uuidSignature,
                    'type' => $value->key,
                ],
            ];
            
            
        } catch (\Exception $e) {
            self::rollbackSignatures($uuidSignature ?? null);
            return (object) [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
        
    }
    
    public static function savePadBase64Image($base64String, $path) {
        
        try {
            if (empty(trim($base64String))) {
                throw new \Exception('Empty base64 string provided.');
            }
            
            [$meta, $content] = explode(',', $base64String, 2);
            
            $checkMeta = self::checkMetaImage($meta);
            if (! $checkMeta->status) {
                throw new \Exception("Unsupported MIME type in base64 metadata: '{$meta}'");
            }
            
            $imageContent = base64_decode($content);
            if (! $imageContent) {
                throw new \Exception('Failed to decode base64 string.');
            }
            
            
            $ext = explode('+', $checkMeta->value)[0];
            $filename = LibHelper::generateUniqueString(64);
            $filenameExt = $filename . '.' . $ext;
            $pathname = $path . '/' . $filenameExt;
            
            
            while (StorageHelper::checkFileExists('signatures', $pathname)) {
                $filename = LibHelper::generateUniqueString(64);
                $filenameExt = $filename . '.' . $ext;
                $pathname = $path . '/' . $filenameExt;
            }
            
            $saveStorage = Storage::disk('signatures')->put($pathname, $imageContent);
            if (! $saveStorage) {
                throw new \Exception('Failed to store image to disk.');
            }
            $size = Storage::disk('signatures')->size($pathname);
            
            $uuidFileSignature = LibHelper::generateUniqueUuId('v7', 'id_file_signature', ModFiles\FileSignature::class);
            
            $saveFiles = ModFiles\FileSignature::create([
                'id_file_signature' => $uuidFileSignature,
                'id_user' => Auth::user()->id_user,
                'type' => $path,
                'file_name' => $filename,
                'file_ext' => $ext,
                'file_path' => $pathname,
                'file_type' => $checkMeta->value,
                'file_size' => $size,
            ]);
            
            if (! $saveFiles) {
                throw new \Exception('Failed to record file metadata in database.');
            }
            
             
            return (object) [
                'status' => true,
                'message' => 'Image saved successfully.',
                'file_info' => (object) [
                    'id_file_signature' => $uuidFileSignature,
                    'id_user' => Auth::user()->id_user,
                    'type' => $path,
                    'file_name' => $filename,
                    'file_ext' => $ext,
                    'file_path' => $pathname,
                    'file_type' => $checkMeta->value,
                    'file_size' => $size,
                ],
                
            ];
            
            
        } catch (\Exception $e) {
            return (object) [
                'status' => false,
                'message' => $e->getMessage(),
                'file_info' => null,
            ];
        }
        
    }
    
    
    private static function checkMetaImage($meta) {
        foreach(self::$acceptMimes as $mime) {
            if (Str::contains($meta, $mime)) {
                return (object) [
                    'status' => true,
                    'value' => $mime,
                ];
            }
        }
        
        return (object) [
            'status' => false,
            'value' => null,
        ];
    }
    
    public static function rollbackSignatures($uuid) {
        
        $signatureFile = Signatures\SignatureFile::where('id_signature', '=', $uuid);
        if ($signatureFile->exists()) self::deleteSignaturesFile( $signatureFile->get() ) ;
        // $checkFileSignature = ModFiles\FileSignature::where('')
        
        Signatures\Signature::where('id_signature', '=', $uuid)->delete();
    }
    
    private static function deleteSignaturesFile($signatureFiles) {
        if ( LibHelper::isCollectionOfModel($signatureFiles, Signatures\SignatureFile::class) ) return;
        foreach($signatureFiles as $signatureFile) {
            $fileS = ModFiles\FileSignature::where('id_file_signature', '=', $signatureFile->id_file_signature)->first();
            // $firstFileS = $fileS->de
            Storage::disk('signatures')->delete($fileS->file_path);
            
            $fileS->delete();
        }
    }
    
}