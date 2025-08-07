<?php

namespace App\Library\Signatures;

use App\Library\Helper as LibHelper;
use App\Library\StorageHelper;
use App\Library\Utils\SchemaUtils;

use Illuminate\Database\Events\DatabaseBusy;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

// use App\Models\Log\UserActivity;
use App\Models\Signatures;
use App\Models\Files as ModFiles;
use App\Models\Users;

class Helper {
    
    private static $acceptMimes = ['svg+xml', 'svg', 'png', 'jpeg', 'webp'];
    private static $acceptOperator = ['=', '!='];
    private static $acceptKey = ['original', 'thumbnail'];
    
    public static function saveSignatures($value, $uuidSignature) {
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
            
            $filename = LibHelper::generateUniqueString(64);
            $path = Auth::user()->id_user . '/' .  $uuidSignature . '/' . $value->key;
            
            $padImages = $value->pad_images;
            if (! self::checkPadImage($padImages)) {
                throw new \Exception("Invalid signature image data.");
            }
            
            foreach ($padImages as $image) {
                
                $imageKey = $image->key;
                $imageValue = $image->value;
                
                $resultExtract = LibHelper::extractBase64($imageValue);
                if (! $resultExtract) {
                    throw new \Exception("Failed to extract base64 data for image key '{$imageKey}'.");
                }
                
                $checkMeta = self::checkMetaImage($resultExtract->meta);
                if (! $checkMeta->status) {
                    throw new \Exception("Unsupported MIME type in base64 metadata: '{$resultExtract->meta}'");
                }
                
                $uuidSignaturePadData = LibHelper::generateUniqueUuId('v7', 'id_signature_pad_data', Signatures\SignaturePadData::class);
                $saveSignaturePadData = Signatures\SignatureDrawings::create([
                    'id_signature_pad_data' => $uuidSignaturePadData,
                    'id_signature_type' => $uuidSignatureType,
                    'pad_key' => $imageKey,
                    'pad_mime' => $resultExtract->mime,
                    'pad_base64' => $resultExtract->base64,
                    'pad_points' => json_encode($value->pad_json),
                ]);
                
                if (! $saveSignaturePadData) {
                    self::rollbackSignatures($uuidSignature);
                    throw new \Exception("Failed to save drawing data for image key '{$imageKey}'.");
                }
                
                $saveFilename = $filename . ($imageKey == 'thumbnail' ? '_thumbnail' : '');
                $ext = explode('+', $checkMeta->value)[0];
                
                $saveResult = self::savePadBase64Image($resultExtract->base64, $value->key, $path, $saveFilename, $ext, $resultExtract->mime);
                
                if (! $saveResult->status) {
                    throw new \Exception("Failed to save base64 image for '{$value->key}': {$saveResult->message}");
                }
                
                $uuidSignatureFile = LibHelper::generateUniqueUuId('v7', 'id_signature_file', Signatures\SignatureFile::class);
                $saveSignatureFile = Signatures\SignatureFile::create([
                    'id_signature_file' => $uuidSignatureFile,
                    'id_signature_type' => $uuidSignatureType,
                    'id_file_signature' => $saveResult->file_info->id_file_signature,
                    'signature_file_key' => $imageKey,
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
    
    public static function savePadBase64Image($base64String, $key, $path, $filename, $ext = 'png', $mime) {
        try {
            if (empty(trim($base64String))) {
                throw new \Exception('Empty base64 string provided.');
            }
            
            $imageContent = base64_decode($base64String, true);
            if ($imageContent === false) {
                throw new \Exception('Failed to decode base64 string.');
            }
            
            $filenameExt = $filename . '.' . $ext;
            $pathname = $path . '/' . $filenameExt;
            
            $saveStorage = Storage::disk('signatures')->put($pathname, $imageContent);
            if (! $saveStorage) {
                throw new \Exception('Failed to store image to disk.');
            }
            
            $size = Storage::disk('signatures')->size($pathname);
            $uuidFileSignature = LibHelper::generateUniqueUuId('v7', 'id_file_signature', ModFiles\FileSignature::class);
            
            $saveFiles = ModFiles\FileSignature::create([
                'id_file_signature' => $uuidFileSignature,
                'id_user' => Auth::user()->id_user,
                'type' => $key,
                'file_name' => $filename,
                'file_ext' => $ext,
                'file_path' => $pathname,
                'file_mime' => $mime,
                'file_size' => $size,
            ]);
            
            if (! $saveFiles) {
                throw new \Exception("Failed to record file metadata in the database.");
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
                    'file_mime' => $mime,
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
    
    public static function checkFile($filename) {
        $resultFiles = ModFiles\FileSignature::where('file_name', '=', $filename);
        if (! $resultFiles->exists()) return null;
        
        $file = $resultFiles->first();
        $pathname = $file->file_path;
        
        $resultStorage = StorageHelper::checkFileExists('signatures', $pathname);
        if (! $resultStorage) return null;
        
        return $resultFiles->first();
    }
    
    public static function rollbackSignatures($uuid) {
        $signatureRollback = Signatures\Signature::where('signatures.id_signature', '=', $uuid);
        self::deleteSignaturesFile($signatureRollback);
        
        $signatureRollback->delete();
    }
    
    private static function checkPadImage($padImages) {
        $count = 0;
        foreach($padImages as $image) {
            if (! in_array($image->key, self::$acceptKey)) continue;
            if (! trim($image->value)) continue;
            
            $count += 1;
        }
        
        return $count == count(self::$acceptKey);
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
    
    private static function deleteSignaturesFile($signatureRollback) {
        
        $signature = $signatureRollback->first();
        if (! $signature ) return;

        $userRoot = $signature->id_user . '/' . $signature->id_signature;
        $storageSelect = Storage::disk('signatures');
        
        $fileIds = Signatures\SignatureType::where('id_signature', $signature->id_signature)
            ->join('signatures_file', 'signatures_type.id_signature_type', '=', 'signatures_file.id_signature_type')
            ->pluck('id_file_signature')
            ->toArray();
        
        
        if (empty($fileIds)) return;
        
        // Hapus semua file dari storage dan database
        foreach ($fileIds as $fileId) {
            $file = ModFiles\FileSignature::find($fileId);
            if (! $file) continue;
            
            // Optional: hapus file fisik jika masih ada
            $filePath = ltrim($file->file_path, '/\\');
            if (Storage::disk('signatures')->exists($filePath)) {
                Storage::disk('signatures')->delete($filePath);
            }
            
            $file->delete();
        }
        
        $storageSelect->deleteDirectory($userRoot);
    }
    
}