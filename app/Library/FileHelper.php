<?php

namespace App\Library;

use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use App\Library\Helper as LibHelper;
use App\Library\User as LibUser;

use App\Models\Files as ModFiles;
use App\Models\Documents;
use App\Models\Signature;
use App\Models\Initial;


class FileHelper {
    public static function saveFile($file, object $clientRequest, object $entity, string $storage, $isChunkUpload = false) { // entity {type, id}, storage {disk, path} | storage disk
        $entityCheck = self::checkEntity($entity);
        if (!$entityCheck->status) {
            return $entityCheck;
        }
        
        $uuidUser = Auth::user()->id_user;
        $uuidFileDisk = LibHelper::generateUniqueUuId('v7', 'id_file_disk', ModFiles\FileDisk::class);
        
        $filename = LibHelper::generateUniqueString(32, 'file_name', ModFiles\FileDisk::class) . "." . $file->getClientOriginalExtension();
        // $clientName = $file->getClientOriginalName();
        $clientNameResponse = self::divideFilenameExtension($file->getClientOriginalName());
        if(!$clientNameResponse->status) {
            return $clientNameResponse;
        }
        $clientNameData = $clientNameResponse->data;
        
        $mimeType = $clientRequest->type;
        $storageFile = self::setStorage($storage, $entity->type);
        
        try {
            $path = $file->storeAs($storageFile->path, $filename, $storageFile->disk);
            if (!$path) {
                throw new \Exception('Could not save file');
            }
            
            if($isChunkUpload) {
                $cleanChunk = self::cleanChunkStorage($file);
                if(!$cleanChunk->status) {
                    throw new \Exception($cleanChunk->message);
                }
            }
            
            // $keyFile = LibHelper::generateUniqueString(rand(36, 64), 'key_file', ModFiles\FileDisk::class);
            $newClientName = self::checkDuplicateClientName($clientNameData->filename, $entity->type);
            $createFileDisk = ModFiles\FileDisk::create([
                'id_file_disk' => $uuidFileDisk,
                // 'key_file' => $keyFile,
                'disk' => $storageFile->disk,
                'path' => $storageFile->path,
                'file_name' => $filename,
                // 'client_name' => $newClientName,
                'extension' => $file->getClientOriginalExtension(),
                'mime_type' => $mimeType,
            ]);
            if (!$createFileDisk) {
                throw new \Exception('Unable to create');
            }
            
            $uuidFileDiskEntity = LibHelper::generateUniqueUuId('v7', 'id_file_disk_entity', ModFiles\FileDiskEntity::class);
            $createFileDiskEntity = ModFiles\FileDiskEntity::create([
                'id_file_disk_entity' => $uuidFileDiskEntity,
                'id_file_disk' => $uuidFileDisk,
                'entity_type' => $entity->type,
                'id_entity' => $entity->id,
                'id_user' => $uuidUser,
                'file_client_name' => $newClientName,
            ]);
            if (!$createFileDiskEntity) {
                throw new \Exception('Unable to create ');
            }
            
            
            $keyFile = LibHelper::generateUniqueString(rand(36, 64), 'key_file', ModFiles\FileDiskKey::class);
            $uuidFileDiskKey = LibHelper::generateUniqueUuId('v7', 'id_file_disk_key', ModFiles\FileDiskKey::class);
            $createFileDiskKey = ModFiles\FileDiskKey::create([
                'id_file_disk_key' => $uuidFileDiskKey,
                'key_file' => $keyFile,
                'entity_type' => $entity->type,
                'id_entity' => $entity->id,
                'id_user' => $uuidUser,
            ]);
            if (!$createFileDiskKey) {
                throw new \Exception('Unable to create ');
            }
            
            LibUser\Activity::createActivity(
                Auth::user()->id_user,
                [
                    'Account', 'Document', 'File'
                ],
                [
                    'title' => 'Upload File ' . ucfirst($entity->type),
                    'type' => 'create',
                    'entity' => 'file_disk',
                    'description' => Auth::user()->userPersonal->fullname . ' uploaded new file at ' . Carbon::now() ,
                    'changes' => [
                        'file_name' => $filename,
                        'file_client_name' => $clientNameData->filename . $clientNameData->extension,
                        'extension' => $file->getClientOriginalExtension(),
                        'message' => 'Upload new File ' . ucfirst($entity->type),
                    ]
                ]
            );
            
            $message = "New file successfully uploaded";
            Log::channel('file_log')->info($message, [
                'filename' => $filename,
                'file_client_name' => $clientNameData->filename . $clientNameData->extension,
                'user_id' => $uuidUser,
                'entity_type' => $entity->type,
                'entity_id' => $entity->id,
                'storage_disk' => $storageFile->disk,
                'storage_path' => $storageFile->path,
                'upload_time' => Carbon::now()->toDateTimeString(),
            ]);
            
            $result = (object) array(
                'status' => true,
                'message' => $message,
                'data' => (object) array(
                    'file_disk' => (object) $createFileDisk->getAttributes(),
                    'file_disk_entity' => (object) $createFileDiskEntity->getAttributes(),
                    'file_info' => (object) array(
                        'file_name' => $filename,
                        'file_client_name' => $clientNameData->filename . $clientNameData->extension,
                        'extension' => $file->getClientOriginalExtension(),
                        'by' => $uuidUser,
                    ),
                ),
            );
            
            return $result;
            
        } catch (\Exception $e) {
            $message = "Failed to upload file. Error encountered while storing file.";
            Log::channel('file_log')->error($message, [
                'file_client_name' => $clientNameData->filename . $clientNameData->extension,
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'upload_time' => Carbon::now()->toDateTimeString(),
            ]);
            
            self::deleteFile($storageFile, $filename);
            
            if (ModFiles\FileDisk::where('id_file_disk', '=', $uuidFileDisk)->exists()) {
                ModFiles\FileDisk::where('id_file_disk', '=', $uuidFileDisk)->delete();
            }
            
            $result = (object) array(
                'status' => false,
                'message' => 'Something error when upload new file',
            );
            
            return $result;
        }
    }
    
    public static function getFile($id_document) {
        
        try {
            
            $modDocument = Documents\Document::where('document.id_document', '=', $id_document);
            
            if (!$modDocument->exists()) {
                throw new \Exception('');
            }
            
            $modDocument = $modDocument->join('document_versions', 
                function($join) {
                    $dbRawString = "(SELECT MAX(version) FROM document_versions AS dv WHERE dv.id_document = document_versions.id_document)";
                    $join->on('document_versions.id_document', '=', 'document.id_document')
                        ->whereColumn('document_versions.version', '=', DB::raw($dbRawString));
                });
            
            if (! $modDocument->exists()) {
                throw new \Exception('');
            }
            
            $modFileDisk = ModFiles\FileDiskEntity::where('file_disk_entity.id_file_disk_entity', '=', $modDocument->value('id_file_disk_entity'));
            if (! $modFileDisk->exists()) {
                throw new \Exception('');
            }
            if (! ModFiles\FileDisk::where('id_file_disk', '=', $modFileDisk->value('id_file_disk'))->exists()) {
                throw new \Exception('');
            }
            
            
            $modFileDisk = $modFileDisk->join('file_disk', 'file_disk.id_file_disk', '=', 'file_disk_entity.id_file_disk');
            $dataModFileDisk = $modFileDisk->first(['file_disk.*']);
            
            return (object) [
                'status' => true,
                'data' => [
                    'file_disk' => $dataModFileDisk,
                ],
            ];
            
            
        } catch (\Exception $e) {
            return (object) [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
    
    public static function checkDuplicateClientName($client_name, $entity_type) {
        $uuidUser = Auth::user()->id_user;
        $list_file = ModFiles\FileDiskEntity::where('id_user', '=', $uuidUser)
            // ->join('file_disk', 'file_disk.id_file_disk', '=', 'file_disk_entity.id_file_disk_entity')
            // ->where('file_disk_entity.entity_type', '=', $entity_type)
            // ->where('file_disk.client_name', 'LIKE', "%{$client_name}%");
            ->where('entity_type', '=', $entity_type)
            ->where('file_client_name', 'LIKE', "%{$client_name}%");
            
        if ($list_file->exists()) {
            return $client_name . " (" . $list_file->count()+1 . ")";
        }
        return $client_name;
    }
    
    public static function deleteFile(object $storage, string $filename) {
        $isFileExists = Storage::disk($storage->disk)->exists($storage->path . $filename);
        if ($isFileExists) {
            Storage::disk($storage->disk)->delete($storage->path . $filename);
        }
    }
    
    public static function checkFile($disk, $path, $filename) {
        return Storage::disk($disk)->exists($path . $filename);
    }
    
    // public static function checkFileByKey($key) {
    //     return ModFiles\FileDiskKey::where('key_file', '=', $key)->exists();
    // }
    
    public static function divideFilenameExtension(string $filename) {
        $acceptType = ['pdf'];
        
        $exp = explode('.', $filename);
        $extIdx = [];
        foreach($exp as $v) {
            if (in_array($v, $acceptType)) {
                array_push($extIdx, array_search($v, $acceptType));
            }
        }
        
        if (!count($extIdx)) {
            return (object) array(
                'status' => false,
                'message' => "File extension doesn't exist or not accepted",
            );
        }
        
        $newFileName = "";
        for ($i = 0; $i < count($exp) - 1; $i++) {
            $newFileName .= $exp[$i];
            if ($i < count($exp) - 2) {
                $newFileName .= " ";
            }
        }
        
        return (object) array(
            'status' => true,
            'data' => (object) array(
                'filename' => $newFileName,
                'extension' => $exp[end($extIdx)],
            ),
        );
    }
    
    // public static function getTemporaryUrlFile(string $disk, string $path, string $filename) {
    //     $path = self::fixPathString($path);
        
    //     try {
            
    //         $storageDisk = Storage::disk($disk);
    //         if (! $storageDisk->exists($path . $filename)) {
    //             throw new \Exception('File not found');
    //         }
            
    //         $tempUrl = Storage::temporaryUrl(
    //             storage_path("app/files/" . $path . $filename),
    //             now()->addMinutes(10),
    //             [
    //                 'ResponseContentDisposition' => 'attachment; filename="'. $filename. '"',
    //             ]
    //         );
            
    //         return (object) [
    //             'status' => true,
    //             'data' => $tempUrl,
    //         ];
            
    //     } catch (\Exception $e) {
            
    //         return (object) [ 
    //             'status' => false,
    //             'message' => $e->getMessage(),
    //         ];
            
    //     }
    // }
    
    public static function getFileByKey($key) {
        try {
            
            $modFilesKey = ModFiles\FileDiskKey::where('file_disk_key.key_file', '=', $key);
            
            if (! $modFilesKey->exists()) {
                throw new \Exception('Key not found');
            }
            
            $modDocs = Documents\Document::where('document.id_document', '=', $modFilesKey->value('id_entity'));
            if (! $modDocs->exists()) {
                throw new \Exception('Document not found');
            }
            
            $modDocs = $modDocs->join('document_versions',
                function($join) {
                    $dbRawString = "(SELECT MAX(version) FROM document_versions AS dv WHERE dv.id_document = document_versions.id_document)";
                    $join->on('document_versions.id_document', '=', 'document.id_document')
                        ->whereColumn('document_versions.version', '=', DB::raw($dbRawString));
                });
            if(! $modDocs->exists()) {
                throw new \Exception('');
            }
            
            $modFileDisk = ModFiles\FileDiskEntity::where('file_disk_entity.id_file_disk_entity', '=', $modDocs->value('id_file_disk_entity'));
            if (! $modFileDisk->exists()) {
                throw new \Exception('File entity not found');
            }
            
            $modFileDisk = $modFileDisk->join('file_disk', 'file_disk.id_file_disk', '=', 'file_disk_entity.id_file_disk');
            if (! $modFileDisk->exists()) {
                throw new \Exception('File disk not found');
            }
            
            $dataModFileDisk = $modFileDisk->first(['file_disk.*']);
            if (! self::checkFile($dataModFileDisk->disk, $dataModFileDisk->path, $dataModFileDisk->file_name)) {
                throw new \Exception('');
            }
            
            $dataModFileDisk->path = self::fixPathString($dataModFileDisk->path);
            
            return (object) [
                'status' => true,
                'message' => '',
                'data' => $dataModFileDisk,
            ];
            
        } catch (\Exception $e) {
            return (object) [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
    
    private static function fixPathString(string $path) {
        if (substr($path, -1) != '/') {
            $path .= '/';
        }
        
        return $path;
    }
    
    private static function setStorage(string $storage, string $entity_type) {
        $uuidUser = Auth::user()->id_user;
        
        $path = "{$uuidUser}/{$entity_type}/";
        
        $acceptDisk = ['private', 'public'];
        $disk = in_array($storage, $acceptDisk) ? $storage : 'private';
        
        return (object) array(
            'disk' => $disk,
            'path' => $path
        );
    }
    
    private static function checkEntity($entity) {
        
        // if (!property_exists($entity, 'type')) {
        //     return (object) array(
        //         'status' => false,
        //         'message' => "Entity type doesn't exist",
        //     );
        // }
        // if (!property_exists($entity, 'id')) {
        //     return (object) array(
        //         'status' => false,
        //         'message' => "Entity id doesn't exist",
        //     );
        // }
        
        // $entityTypes = ['document', 'signature', 'initial'];
        // if (!in_array($entity->type, $entityTypes)) {
        //     return (object) array(
        //         'status' => false,
        //         'message' => "Entity type({$entity->type}) doesn't exist in the accept type list",
        //     );
        // }
        
        // switch($entity->type) {
        //     case "document":
        //         $uuidDocument = $entity->id;
        //         $isDocumentExists = Documents\Document::where('id_document', '=', $uuidDocument)->exists();
        //         if (!$isDocumentExists) {
        //             return (object) array(
        //                 'status' => false,
        //                 'message' => "Document with id({$uuidDocument}) doesn't exist",
        //             );
        //         }
        //         break;
                
        //     case "signature":
        //         $uuidSignature = $entity->id;
        //         $isSignatureExists = Signature\Signature::where('id_signature', '=', $uuidSignature)->exists();
        //         if (!$isSignatureExists) {
        //             return (object) array(
        //                 'status' => false,
        //                 'message' => "Signature with id({$uuidSignature}) doesn't exist",
        //             );
        //         }
        //         break;
                
        //     case "initial":
        //         $uuidInitial = $entity->id;
        //         $isInitialExists = Initial\Initial::where('id_initial', '=', $uuidInitial)->exists();
        //         if (!$isInitialExists) {
        //             return (object) array(
        //                 'status' => false,
        //                 'message' => "Initial with id({$uuidInitial}) doesn't exist",
        //             );
        //         }
        //         break;
                
        //     default:
        //         return (object) array(
        //             'status' => false,
        //             'message' => "Entity type({$entity->type}) doesn't exist in the accept type list",
        //         );
        // }
        
        $entityTypes = [
            'document' => Documents\Document::class,
            'signature' => Signature\Signature::class,
            'initial' => Initial\Initial::class,
        ];
    
        if (!isset($entityTypes[$entity->type])) {
            return (object)['status' => false, 'message' => "Invalid entity type"];
        }
    
        $model = $entityTypes[$entity->type];
        if (!$model::where('id_' . $entity->type, $entity->id)->exists()) {
            return (object)['status' => false, 'message' => "Entity not found"];
        }
        
        return (object) array(
            'status' => true,
            'message' => "Entity type and id exists",
        );
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