<?php

namespace App\Library\Documents;


use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use App\Library\Helper as LibHelper;
use App\Library\User as UserLibrary;
use App\Library\FileHelper;

use App\Models\Documents;
use App\Models\Files as FilesMod;
use App\Models\Files\FileDiskEntity;
use App\Models\User\User;

class DocsHelper {
    
    /**
    * Saves a file to the storage disk and creates a record in the database.
    *
    * @param $file The uploaded file to be saved.
    * @return void
    */
    public static function saveFile($file) {
        // $file->getClientOriginalName(),
        // $file->getClientMimeType(),
        // $file->getClientOriginalExtension(),
        // $file->getClientOriginalPath(),
        // $file->getATime(),
        
        $uuidUser = Auth::user()->id_user;
        $filename = LibHelper::generateUniqueString(32, 'file_name', FilesMod\FileDisk::class) . "." . $file->getClientOriginalExtension();
        $clientName = $file->getClientOriginalName();
        $mimeType = $file->getClientMimeType();
        // $strgDisk = "public";
        $strgDisk = "private";
        $strgPath = "documents/{$uuidUser}/";
        $message = "";
        
        try {
            $path = $file->storeAs($strgPath, $filename, $strgDisk);
            if (!$path) {
                throw new \Exception('Could not save file');
            }
            
            $cleanChunk = self::cleanChunkStorage($file);
            if(!$cleanChunk->status) {
                throw new \Exception($cleanChunk->message);
            }
            
            
            $uuidDocument = LibHelper::generateUniqueUuId('v4', 'id_document', Documents\Document::class);
            $createDocs = Documents\Document::create([
                'id_document' => $uuidDocument, 
                'id_user' => $uuidUser, 
                'status_at' => json_encode(array(
                    'upload_at' => Carbon::now(),
                )),
            ]);
            
            if (!$createDocs) {
                throw new \Exception('Could not create documents');
            }
            
            $keyFile = LibHelper::generateUniqueString(36, 'key_file', FilesMod\FileDisk::class);
            $uuidFileDisk = LibHelper::generateUniqueUuId('v7', 'id_file_disk', FilesMod\FileDisk::class);
            $newClientName = self::checkDuplicateClientName($clientName);
            FilesMod\FileDisk::create([
                'id_file_disk' => $uuidFileDisk,
                'key_file' => $keyFile,
                'disk' => $strgDisk,
                'path' => $strgPath,
                'file_name' => $filename,
                'client_name' => $newClientName,
                'extension' => $file->getClientOriginalExtension(),
                'mime_type' => $mimeType,
            ]);
            
            $uuidFileDiskEntity = LibHelper::generateUniqueUuId('v7', 'id_file_disk_entity', FilesMod\FileDiskEntity::class);
            FilesMod\FileDiskEntity::create([
                'id_file_disk_entity' => $uuidFileDiskEntity,
                'id_file_disk' => $uuidFileDisk,
                'entity_type' => 'document',
                'id_entity' => $uuidDocument,
                'id_user' => $uuidUser,
            ]);
            
            $message = "Document successfully uploaded to storage";
            
            Documents\DocumentVersions::create([
                'id_document_versions' => LibHelper::generateUniqueUuId('v4', 'id_document_versions', Documents\DocumentVersions::class),
                'id_document' => $uuidDocument,
                'id_file_disk' => $uuidFileDisk, 
                'changes' => json_encode(array(
                    'file_name' => $filename,
                    'client_name' => $clientName,
                    'extension' => $file->getClientOriginalExtension(),
                    'message' => 'Upload document',
                )), 
            ]);
            
            UserLibrary\Activity::createActivity(
                Auth::user()->id_user,
                [
                    'Account', 'Document'
                ],
                [
                    'title' => 'Upload Document',
                    'type' => 'authenticate',
                    'entity' => 'account',
                    'description' => Auth::user()->userPersonal->fullname . ' login at ' . Carbon::now() ,
                    'changes' => [
                        'file_name' => $filename,
                        'client_name' => $clientName,
                        'extension' => $file->getClientOriginalExtension(),
                        'message' => 'Upload document',
                    ]
                ]
            );
            
            // $uuidDocsDisk = LibHelper::generateUniqueUuId('v7', 'id_document_disk', Documents\DocumentDisk::class);
            // $newClientName = self::checkDuplicateClientName($clientName);
            // Documents\DocumentDisk::create([
            //     'id_document_disk' => $uuidDocsDisk,
            //     'id_document' => $uuidDocument,
            //     'disk' => $strgDisk,
            //     'path' => "storage/{$strgPath}",
            //     'file_name' => $filename,
            //     'client_name' => $newClientName,
            //     'extension' => $file->getClientOriginalExtension(), 
            // ]);
            
            // $message = "Document successfully uploaded to storage disk({$strgDisk}) at path({$strgPath})";
            Log::channel('document_log')->info($message, [
                'filename' => $filename,
                'client_name' => $clientName,
                'user_id' => $uuidUser,
                'document_id' => $uuidDocument,
                'upload_time' => Carbon::now()->toDateTimeString(),
                'storage_disk' => $strgDisk,
                'storage_path' => $strgPath,
            ]);
            
        } catch (\Exception $e) {
            $message = "Failed to upload document. Error encountered while storing file.";
            Log::channel('document_log')->error($message, [
                'client_name' => $clientName,
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'upload_time' => Carbon::now()->toDateTimeString(),
            ]);
            Documents\Document::where('id_document', '=', $uuidDocument)->delete();
        }
    }
    
    public static function newDocument($file, $clientRequest) {
        $uuidUser = Auth::user()->id_user;
        
        $uuidDocument = LibHelper::generateUniqueUuId('v4', 'id_document', Documents\Document::class);
        $createDocs = Documents\Document::create([
            'id_document' => $uuidDocument, 
            'id_user' => $uuidUser, 
            'status_at' => json_encode(array(
                'upload_at' => Carbon::now(),
            )),
        ]);
        
        if (!$createDocs) {
            return (object) array(
                'status' => false,
                'message' => 'Could not create documents'
            );
        }
        
        $entity = (object) array(
            'type' => 'document',
            'id' => $uuidDocument,
        );
        $storage = 'private';
        $responseFileHelper = FileHelper::saveFile($file, $clientRequest, $entity, $storage, true);
        
        if (!$responseFileHelper->status) {
            Documents\Document::where('id_document', '=', $uuidDocument)->delete();
            return (object) array(
                'status' => false,
                'message' => 'Something error when save new document'
            );
        }
        
        $dataResponse = $responseFileHelper->data;
        $uuidDocumentVersions = LibHelper::generateUniqueUuId('v7', 'id_document_versions', Documents\DocumentVersions::class);
        $createDocsVersions = Documents\DocumentVersions::create([
            'id_document_versions' => $uuidDocumentVersions, 
            'id_document' => $uuidDocument,
            // 'id_file_disk' => $dataResponse->file_disk->id_file_disk,  
            'id_file_disk_entity' => $dataResponse->file_disk_entity->id_file_disk_entity,  
            'changes' => json_encode(array(
                'message' => 'Upload new file Document',
                'file_info' => $dataResponse->file_info,
            )),
        ]);
        
        
        if (!$createDocsVersions) {
            Documents\Document::where('id_document', '=', $uuidDocument)->delete();
            $newFileDisk = FilesMod\FileDisk::where('id_file_disk', '=', $dataResponse->file_disk->id_file_disk);
            $dataNewFileDisk = $newFileDisk->first();
            $objStorage = (object) array(
                'disk' => $dataNewFileDisk->disk,
                'path' => $dataNewFileDisk->path
            );
            
            FileHelper::deleteFile($objStorage, $dataNewFileDisk->file_name);
            $newFileDisk->delete();
            
            return (object) array(
                'status' => false,
                'message' => 'Something error when save new document versions'
            );
        }
        
        return (object) array(
            'status' => true,
            'message' => 'Upload new file successfully',
        );
    }
    
    // public static function checkDuplicateClientName($file_client_name) {
    public static function checkDuplicateClientName($client_name) {
        $uuidUser = Auth::user()->id_user;
        $list_document = FilesMod\FileDisk::where('id_user', '=', $uuidUser)
            ->join('file_disk_entity', 'file_disk.id_file_disk', '=', 'file_disk_entity.id_file_disk_entity')
            ->where('file_disk_entity.entity_type', '=', $client_name)
            ->where('file_disk.client_name', 'LIKE', "%{$client_name}%");
        
        // $list_document = Documents\Document::where('id_user', '=', $uuidUser)
        //     ->join('document_disk', 'document.id_document', '=', 'document_disk.id_document')
        //     ->where('document_disk.client_name', 'LIKE', "%{$client_name}%");
        
        if ($list_document->exists()) {
            return $client_name . " (" . $list_document->count()+1 . ")";
        }
        return $client_name;
    }
    
    /**
     * Retrieve document files based on version and user criteria.
     *
     * @param string|int $version The version of the document to retrieve:
     *                            - 'latest': Most recent version (default)
     *                            - 'first': Oldest version
     *                            - integer: Specific version number
     * @param string|null $user The user ID to filter documents by (optional)
     * @param string|null $filter 
     * @param string|null $search 
     * @param string|null $status 
     * @return Documents\Document Query builder for document files
     */
    public static function getDocumentFile($version = 'latest', $user = null, $filter = null, $search = null, $status = null) {
        $listDocument = Documents\Document::join('document_disk', 
            function($join) use($version) {
                $dbRawString = '';
                switch(gettype($version)) {
                    case 'string':
                        switch($version) {
                            case 'latest':
                                $dbRawString = "(SELECT MAX(version) FROM document_disk AS dd WHERE dd.id_document = document_disk.id_document)";
                                break;
                            case 'first':
                                $dbRawString = "(SELECT MIN(version) FROM document_disk AS dd WHERE dd.id_document = document_disk.id_document)";
                                break;
                            default:
                                $dbRawString = "(SELECT MIN(version) FROM document_disk AS dd WHERE dd.id_document = document_disk.id_document)";
                                break;
                        }
                        break;
                    case 'integer':
                        $dbRawString = "(SELECT MIN(version) FROM document_disk AS dd WHERE dd.id_document = document_disk.id_document AND dd.version = {$version})";
                        break;
                    default:
                        $dbRawString = "(SELECT MAX(version) FROM document_disk AS dd WHERE dd.id_document = document_disk.id_document)";
                        break;
                }
                
                $join->on('document.id_document', '=', 'document_disk.id_document')
                    ->whereColumn('document_disk.version', '=', DB::raw($dbRawString));
            })
            ->orderBy('document_disk.client_name')
            ->select(['document_disk.*', 'document.created_at as doc_created_at', 'document.updated_at as doc_updated_at']);
        
        if ($user) {
            $listDocument->where('id_user', '=', $user);
        }
        
        switch($filter) {
            case 'latest':
                $listDocument->orderBy('document.updated_at', 'DESC');
                break;
            case 'oldest':
                $listDocument->orderBy('document.updated_at', 'ASC');
                break;
            default:
                $listDocument->orderBy('document.updated_at', 'DESC');
                break;
        }
        
        $statusRegis = ['Draft', 'Pending', 'Approved', 'Rejected'];
        $status = ucfirst($status);
        if (in_array($status, $statusRegis)) {
            $listDocument->where('document.document_status', '=', $status);
        }
        
        if ($search) {
            $listDocument->where('document_disk.client_name', 'LIKE', "%{$search}%");
        }
        
        return $listDocument;
    }
    
    public static function getDocumentFileV2($version = 'latest', $user = null, $filter = null, $status = null) {
        $listDocument = FilesMod\FileDiskEntity::where('file_disk_entity.entity_type', '=', 'document')
            ->join('file_disk_key', 'file_disk_key.id_entity', '=', 'file_disk_entity.id_entity')
            ->join('file_disk', 'file_disk.id_file_disk', '=', 'file_disk_entity.id_file_disk')
            ->join('document_versions', 
                function($join) use($version) {
                    // $dbRawString = '';
                    // switch(gettype($version)) {
                    //     case 'string':
                    //         switch($version) {
                    //             case 'latest':
                    //                 $dbRawString = "(SELECT MAX(version) FROM document_versions AS dv WHERE dv.id_document = document_versions.id_document)";
                    //                 break;
                    //             case 'first':
                    //                 $dbRawString = "(SELECT MIN(version) FROM document_versions AS dv WHERE dv.id_document = document_versions.id_document)";
                    //                 break;
                    //             default:
                    //                 $dbRawString = "(SELECT MIN(version) FROM document_versions AS dv WHERE dv.id_document = document_versions.id_document)";
                    //                 break;
                    //         }
                    //         break;
                    //     case 'integer':
                    //         $dbRawString = "(SELECT MIN(version) FROM document_versions AS dv WHERE dv.id_document = document_versions.id_document AND dv.version = {$version})";
                    //         break;
                    //     default:
                    //         $dbRawString = "(SELECT MAX(version) FROM document_versions AS dv WHERE dv.id_document = document_versions.id_document)";
                    //         break;
                    // }
                    
                    $dbRawString = "(SELECT MAX(version) FROM document_versions AS dv WHERE dv.id_document = document_versions.id_document)";
                    $join->on('document_versions.id_file_disk_entity', '=', 'file_disk_entity.id_file_disk_entity')
                        ->whereColumn('document_versions.version', '=', DB::raw($dbRawString));
                }
            )
            ->join('document', 'document.id_document', '=', 'document_versions.id_document')
            ->orderBy('file_disk_entity.file_client_name')
            ;
        
        if ($user) {
            $listDocument->where('file_disk_entity.id_user', '=', $user);
        }
        
        $listDocument->select([
            'file_disk.id_file_disk',
            // 'file_disk.key_file',
            'file_disk.extension',
            'file_disk.disk',
            'file_disk.path',
            'file_disk.file_name',
            'file_disk_entity.id_file_disk_entity',
            'file_disk_entity.file_client_name',
            'file_disk_key.*',
            
            'document.document_status',
            'document_versions.id_document_versions',
            'document_versions.id_document',
            'document_versions.version',
            
            'document.created_at as doc_created_at', 
            'document_versions.updated_at as doc_updated_at'
        ]);
        
        // dump($listDocument->get());
        
        return $listDocument;
    }
    
    public static function getDocumentSearchFile($user = null, $search = null, $status = null) {
        $listDocument = FilesMod\FileDiskEntity::where('file_disk_entity.entity_type', '=', 'document')
            ->join('file_disk', 'file_disk.id_file_disk', '=', 'file_disk_entity.id_file_disk')
            ->join('document_versions', 'document_versions.id_file_disk_entity', '=', 'file_disk_entity.id_file_disk_entity')
            ->join('document', 'document.id_document', '=', 'document_versions.id_document')
            ->orderBy('document_versions.version', 'desc');
        
        if ($user) {
            $listDocument->where('file_disk_entity.id_user', '=', $user);
        }
        
        $statusRegis = ['Draft', 'Pending', 'Approved', 'Rejected'];
        $status = ucfirst($status);
        if (in_array($status, $statusRegis)) {
            $listDocument->where('document.document_status', '=', $status);
        }
        
        if ($search) {
            $listDocument->where('file_disk_entity.file_client_name', 'LIKE', "%{$search}%");
        }
        
        $listDocument->select([
            'file_disk.id_file_disk',
            'file_disk.key_file',
            'file_disk.extension',
            'file_disk.disk',
            'file_disk.path',
            'file_disk.file_name',
            'file_disk_entity.id_file_disk_entity',
            'file_disk_entity.file_client_name',
            
            'document.document_status',
            'document_versions.id_document_versions',
            'document_versions.id_document',
            'document_versions.version',
            
            'document.created_at as doc_created_at', 
            'document_versions.updated_at as doc_updated_at'
        ]);
        
        // dump($listDocument->get());
        
        return $listDocument;
    }
    
    public static function getDocumentAllFile($user = null) {
        $listDocument = Documents\Document::join('document_disk', 'document_disk.id_document', '=', 'document.id_document')
            ->orderBy('document_disk.client_name')
            ->select('document_disk.*');
        
        if ($user) {
            $listDocument->where('id_user', '=', $user);
        }
        
        return $listDocument;
    }
    
    public static function getDocumentFileById($id_document) {
        try {
            $documentModel = Documents\Document::where('document.id_document', '=', $id_document);
            if (!$documentModel->exists()) {
                throw new \Exception('Document not found');
            }
            
            $authorDocument = (object) [
                'status' => false,
                'data' => (object) [
                    'fullname' => 'User not found',
                ]
            ];
            
            $userDocument = User::where('user.id_user', '=', $documentModel->value('id_user'))
                ->join('user_personal', 'user_personal.id_user', '=', 'user.id_user');
            if ($userDocument->exists()) {
                $authorDocument = (object) [
                    'status' => true,
                    'data' => $userDocument->first([
                        'user.email',
                        'user_personal.*'
                    ]),
                ];
            }
            
            
            $documentModel->join('document_versions', function($join) {
                $dbRawString = "(SELECT MAX(version) FROM document_versions AS dv WHERE dv.id_document = document_versions.id_document)";
                $join->on('document_versions.id_document', '=', 'document.id_document')
                    ->whereColumn('document_versions.version', '=', DB::raw($dbRawString));
            });
            
            $idFileDiskEntity = $documentModel->value('id_file_disk_entity');
            // $fileDiskEntityModel = FilesMod\FileDiskEntity::where('file_disk_entity.id_file_disk_entity', '=', $idFileDiskEntity);
            // if (! $fileDiskEntityModel->exists()) {
            //     throw new \Exception('File Entity not found');
            // }
            
            // $fileDiskModel = FilesMod\FileDisk::where('id_file_disk', '=', $fileDiskEntityModel->value('id_file_disk'));
            $fileDiskModel = FilesMod\FileDiskEntity::where('file_disk_entity.id_file_disk_entity', '=', $idFileDiskEntity)
                ->join('file_disk', 'file_disk.id_file_disk', '=', 'file_disk_entity.id_file_disk');
            if (! $fileDiskModel->exists()) {
                throw new \Exception('File not found');
            }
            
            return (object) [
                'status' => true,
                'message' => 'Document and file found',
                // 'data' => (object) [
                //     'document' => $documentModel->first(),
                //     'file_disk' => $fileDiskModel->first(),
                //     // 'file_disk_entity' => $fileDiskEntityModel->first(),
                // ],
                // 'data' => $fileDiskModel->first([
                //     'file_disk.*',
                //     'file_disk_entity.file_client_name'
                // ]),
                'data' => (object) [
                    'file' => $fileDiskModel->first([
                        'file_disk.*',
                        'file_disk_entity.file_client_name'
                    ]),
                    'author' => $authorDocument,
                ],
            ];

        } catch(\Exception $e) {
            return (object) [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
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