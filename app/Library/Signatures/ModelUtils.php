<?php

namespace App\Library\Signatures;

use App\Library\Helper as LibHelper;
use App\Library\User\Helper as UserHelper;
use App\Library\Signatures\Helper as SignatureHelper;
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
use Illuminate\Validation\Rules\Exists;

class ModelUtils {
    
    private static $acceptMimes = ['svg+xml', 'svg', 'png', 'jpeg', 'webp'];
    private static $acceptOperator = ['=', '!='];
    private static $acceptKey = ['original', 'thumbnail'];
    
    
    
    public static function getSignatures($id_user, $default = false, $get = true) {
        if (! UserHelper::checkUser($id_user)) return null;
        
        $model = Signatures\Signature::where('signatures.id_user', '=', $id_user);
                
        if ($default == true) {
            $model->where('default', '=', $default);
        }
        
        $model->join('signatures_type', 'signatures.id_signature', '=', 'signatures_type.id_signature');
        
        $data = $get ? $model->get() : $model->first();

        return $data->isEmpty() ? null : $data;
    }
    
    public static function getSignaturesType($id_signature, $by_key = true) {
        if (! self::checkSignature($id_signature)) return null;
        
        $model = Signatures\SignatureType::where('id_signature', '=', $id_signature)
            ->whereIn('type', ['signature', 'paraf']);
            
        if (! $model->count()) return null;
        
        return $by_key ? $model->get()->keyBy('type') : $model->get();
    }
    
    public static function getSignaturesPadData($id_signature_type) {
        if (! self::checkSignatureType($id_signature_type)) return null;
        
        $model = Signatures\SignaturePadData::where('id_signature_type', '=', $id_signature_type);
        if (! $model->count()) return null;
        
        return $model->get();
    }
    
    
    
    public static function getListSignaturesPadData($by_user = 'all', $default = false, $pad_key = 'original', $by_key = true) {
        if (! in_array($pad_key, self::$acceptKey)) return null;
        
        $listSignatures = Signatures\Signature::query();
        if (($by_user != 'all') && UserHelper::checkUser($by_user)) {
            $listSignatures->where('signatures.id_user', '=', $by_user);
        }
        
        if ($default == true) {
            $listSignatures->where('signatures.default', '=', true);
        }
        
        if (! $listSignatures->exists()) return null;
        
        $dataList = (clone $listSignatures)->get();
        $data = [];
        foreach($dataList as $signature) {
            $signatureType = Signatures\SignatureType::where('id_signature', '=', $signature->id_signature)
                ->join('signatures_pad_data', 'signatures_pad_data.id_signature_type', '=', 'signatures_type.id_signature_type')
                ->where('signatures_pad_data.pad_key', '=', $pad_key)
                ->whereIn('signatures_type.type', ['signature', 'paraf'])
            ;
            
            $tmp = $by_key ? $signatureType->get()->keyBy('type') : $signatureType->get();
            if ($tmp->isEmpty()) continue;
            
            $data[] = json_decode( json_encode(array_merge( ['id'=> $signature->id_signature], $tmp->toArray() ) ));
            
            // $getData = json_decode(json_encode($tmp->toArray()));
            // $data[] = (object) [
            //     'id_signature' => $signature->id_signature,
            //     'list_data' => $getData,
            // ];
        }
        
        return collect($data)->isEmpty() ? null : $data;
    }
    
    public static function getListSignaturesImages($by_user = 'all', $default = false, $ext = 'png', $by_key = true) {
        if (! in_array($ext, self::$acceptMimes)) return null;
        
        $listSignatures = Signatures\Signature::query();
        if (($by_user != 'all') && UserHelper::checkUser($by_user)) {
            $listSignatures->where('signatures.id_user', '=', $by_user);
        }
        
        if ($default == true || $default == false) {
            $listSignatures->where('signatures.default', '=', $default);
        }
        
        
        if (! $listSignatures->exists()) return null;
        
        $dataList = (clone $listSignatures)->get();
        $data = [];
        foreach($dataList as $signature) {
            $signatureType = Signatures\SignatureType::where('id_signature', '=', $signature->id_signature)
                ->join('signatures_file', 'signatures_file.id_signature_type', '=', 'signatures_type.id_signature_type')
                ->join('files_signature', 'files_signature.id_file_signature', '=', 'signatures_file.id_file_signature')
                ->where('files_signature.file_ext', '=', $ext)
                ->where('files_signature.file_name', 'NOT LIKE', "%_thumbnail")
                ->whereIn('signatures_type.type', ['signature', 'paraf'])
            ;
            
            $tmp = $by_key ? $signatureType->get()->keyBy('type') : $signatureType->get();
            
            if ($tmp->isEmpty()) continue;
            
            // $getData = json_decode(json_encode($tmp->toArray()));
            $data[] = json_decode( json_encode(array_merge( ['id'=> $signature->id_signature], $tmp->toArray() ) ));
            // $data[] = (object) [
            //     'id_signature' => $signature->id_signature,
            //     'list_data' => $getData,
            // ];
        }
        
        return collect($data)->isEmpty() ? null : $data;
    }
    
    
    
    
    public static function getSignaturesImage($id_signature, $file_ext = 'png', $default = false) {
        
        // $signature = Signatures\Signature::where('signatures.id_signature', '=', $id_signature)
        //     ->join('signatures_file', 'signatures.id_signature', '=', 'signatures_file.id_signature')
        //     ->join('files_signature', 'files_signature.id_file_signature', '=', 'signatures_file.id_file_signature');
        
        // if ($file_ext) {
        //     $signature->where('file_ext', '=', $file_ext);
        // }
            
        // return $signature->get();
        
        return Signatures\Signature::where('signatures.id_signature', '=', $id_signature)
            ->join('signatures_file', 'signatures.id_signature', '=', 'signatures_file.id_signature')
            ->join('files_signature', 'files_signature.id_file_signature', '=', 'signatures_file.id_file_signature')
            ->where('file_ext', '=', $file_ext)
            ->where('default', '=', $default)
            ->where('file_name', 'NOT LIKE', '%_thumbnail')
            ->whereIn('type', ['signature', 'paraf'])
            // ->select('files_signature.*', 'signatures.id_signature', 'type') // pastikan select-nya lengkap
            ->get()
            ->keyBy('type'); // mengubah hasil jadi key-value berdasarkan 'type'
        
    }
    
    public static function countSignatures($id_user = 'all') {
        if (trim($id_user) == 'all') return Signatures\Signature::count();
        if (! Users\User::where('id_user', '=', $id_user)->exists()) return null;
        
        return Signatures\Signature::where('id_user', '=', $id_user)->count();
    }
    
    public static function updateSignatureDefault($id, $id_user) {
        try {
            if (! self::checkSignature($id)) {
                throw new \Exception('Signature not found.');
            }
            
            $selectSignature = Signatures\Signature::where('id_user', '=', $id_user);
            if (! $selectSignature->exists()) {
                throw new \Exception('User does not have any signatures.');
            }
            
            $reset = $selectSignature->update(['default' => false]);
            if (! $reset) {
                throw new \Exception('Failed to reset existing default signatures.');
            }
            
            $update = Signatures\Signature::where('id_signature', $id)
                ->where('id_user', $id_user)
                ->update(['default' => true]);
            
            if (! $update) {
                throw new \Exception('Failed to set the selected signature as default.');
            }
            
            return (object) [
                'status' => true,
                'message' => 'Signature has been successfully set as default.',
            ];
            
        } catch (\Exception $e) {
            return (object) [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
    
    public static function checkSignatureUser($id_user, $id_signature) {
        return Signatures\Signature::find($id_signature)->where('id_user', '=', $id_user)->exists();
    }
    public static function checkSignature($id) {
        return Signatures\Signature::find($id)->exists();
    }
    
    public static function checkSignatureType($id) {
        return Signatures\SignatureType::find($id)->exists();
    }
    
    
    
    public static function deleteSignature($id, $id_user) {
        try {
            if (! self::checkSignature($id)) {
                throw new \Exception('Signature not found.');
            }
            
            $selectSignature = Signatures\Signature::where('id_user', '=', $id_user);
            if (! $selectSignature->exists()) {
                throw new \Exception('User does not have any signatures to delete.');
            }
            
            $resultDeleteFile = self::deleteFilesSignature($id);
            if (! $resultDeleteFile->status) {
                throw new \Exception($resultDeleteFile->message);
            }
            
            $delete = Signatures\Signature::where('id_signature', $id)
                ->where('id_user', $id_user)
                ->delete();
            
            if (! $delete) {
                throw new \Exception('Failed to delete signature from database.');
            }
            
            return (object) [
                'status' => true,
                'message' => 'Signature has been deleted successfully.',
            ];
            
        } catch (\Exception $e) {
            return (object) [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
    
    
    
    
    private static function deleteFilesSignature($id) {
        
        try {
            
            if (! self::checkSignature($id)) {
                throw new \Exception('Signature not found.');
            }
            
            $signature = Signatures\Signature::where('id_signature', '=', $id)->first();
            
            $userRoot = $signature->id_user . '/' . $signature->id_signature;
            $storageSelect = Storage::disk('signatures');
            
            $signatureFile = Signatures\SignatureType::where('id_signature', $id)
                ->join('signatures_file', 'signatures_type.id_signature_type', '=', 'signatures_file.id_signature_type')
                ->pluck('id_file_signature')
                ->toArray();
            
            if (empty($signatureFile)) {
                throw new \Exception('Failed to delete associated signature files.');
            }
            
            foreach ($signatureFile as $fileId) {
                $file = ModFiles\FileSignature::find($fileId);
                if (! $file) continue;
                
                $filePath = ltrim($file->file_path, '/\\');
                if (Storage::disk('signatures')->exists($filePath)) {
                    Storage::disk('signatures')->delete($filePath);
                }
                
                $file->delete();
            }
            
            $storageSelect->deleteDirectory($userRoot);
            
            return (object) [
                'status' => true,
                'message' => 'Signature files have been deleted successfully.',
            ];
            
        } catch (\Exception $e) {
            return (object) [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
        
    }
    
    
}