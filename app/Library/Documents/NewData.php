<?php

namespace App\Library\Documents;


use Exception;
use Illuminate\Database\QueryException;
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

use App\Models\Documents;
use App\Models\Files as FilesMod;
use App\Models\Jobs\Documents\Process;

use Ramsey\Uuid\Uuid;
// use
use Symfony\Component\Mime\MimeTypes;

class NewData {
    
    protected static $config;
    protected static $mapFileMeta = ['originalName', 'originalExt', 'mime', 'size', 'realPath', 'relativePath', 'basename'];
    protected static $status = [
        'process' => 'process', 
        'success' => 'success', 
        'failed' => 'failed', 
        'failure' => 'failure', 
        'cancelled' => 'cancelled'
    ];
    private static function loadConfig() {
        self::$config = config('custom_upload.documents');
    }
    
    /**
     * @var array $fileMeta originalName, originalExt, mime, size, realPath, relativePath, basename
     * @var string $originalName
     * @var string $ownerId
     * 
     * @return object
     */
    public static function saveNewDataDocuments($fileMeta, $originalName, $ownerId) {
        self::loadConfig();
        Log::channel('library_log')
            ->debug("App\\Library\\Documents\\NewData", [
                'param' => [
                    'file' => $fileMeta, 
                    'original' => $originalName, 
                    'owner' => $ownerId
                ],
            ]);
        
        Log::channel('library_log_documents')
            ->info('[Start] Save new data documents', [
                'param' => [
                    'file' => $fileMeta, 
                    'original' => $originalName, 
                    'owner' => $ownerId
                ],
            ]);
        
        $uuidDocuments = LibHelper::generateUniqueUuId('v4', 'id_document', Documents\Document::class);
        
        try {
            // 
            if (! self::checkFileMeta($fileMeta)) {
                throw new Exception(
                    json_encode([
                        'status' => self::$status['failure'],
                        'retryable' => false,
                        'message' => 'Invalid or incomplete file metadata',
                        'exception' => 'file_meta.invalid',
                    ])
                );
            }
            
            if (!in_array($fileMeta['mime'], self::$config['accept'])) {
                throw new Exception(
                    json_encode([
                        'status' => self::$status['failure'],
                        'retryable' => false,
                        'message' => 'Unsupported MIME type: ' . $fileMeta['mime'],
                        'exception' => 'mime.unsupported',
                    ])
                );
            }
            
            $resultNewDocs = Documents\Document::create([
                'id_document' => $uuidDocuments,
                // 'id_document' => '0e888840-78c7-4e7c-9e2d-6301a070abb9',
                'owner_id' => $ownerId,
            ]); 
            if (! $resultNewDocs) {
                throw new Exception(
                    json_encode([
                        'status' => self::$status['failed'],
                        'retryable' => true,
                        'message' => 'Failed to create main document record',
                        'exception' => 'document.create.failed',
                    ])
                );
            }
            
            $originalName = pathinfo($originalName, PATHINFO_FILENAME);
            $resultCheckName = Documents\Document::where('documents.owner_id', '=', $ownerId)
                ->join('documents_information', 'documents_information.id_document', '=', 'documents.id_document')
                ->where('documents_information.name', '=', $originalName);
            $nameVersion = 1;
            if ($resultCheckName->exists()) {
                $lastVersion = $resultCheckName->latest('name_version')->first();
                $nameVersion = $lastVersion->name_version + 1;
            }
            
            $resultNewDocsInfo = Documents\DocumentInformation::create([
                'id_document' => $uuidDocuments,
                'name' => $originalName,
                'name_version' => $nameVersion,
            ]);
            
            if (! $resultNewDocsInfo) {
                throw new Exception(
                    json_encode([
                        'status' => self::$status['failed'],
                        'retryable' => true,
                        'message' => 'Failed to create document information',
                        'exception' => 'document_info.create.failed',
                    ])
                );
            }
            
            $resultNewDocsPubs = Documents\DocumentPublicity::create([
                'id_document' => $uuidDocuments,
                'status_publicity' => 'private',
            ]);
            
            if (! $resultNewDocsPubs) {
                throw new Exception(
                    json_encode([
                        'status' => self::$status['failed'],
                        'retryable' => true,
                        'message' => 'Failed to create document publicity record',
                        'exception' => 'document_publicity.create.failed',
                    ])
                );
            }
            
            $uuidDocsCollabs = LibHelper::generateUniqueUuId('v7', 'id_document_collaborator', Documents\DocumentCollaborator::class);
            $resultNewDocsCollabs = Documents\DocumentCollaborator::create([
                'id_document_collaborator' => $uuidDocsCollabs,
                'id_user' => $ownerId,
                'id_document' => $uuidDocuments,
                'role' => 'signer',
            ]);
            if (! $resultNewDocsCollabs) {
                throw new Exception(
                    json_encode([
                        'status' => self::$status['failed'],
                        'retryable' => true,
                        'message' => 'Failed to create document collaborator',
                        'exception' => 'document_collaborator.create.failed',
                    ])
                );
            }
            
            $uuidDocsVersion = LibHelper::generateUniqueUuId('v7', 'id_document_version', Documents\DocumentVersions::class);
            $resultNewDocsVers = Documents\DocumentVersions::create([
                'id_document_version' => $uuidDocsVersion,
                'id_document' => $uuidDocuments,
                'version' => 1,
            ]);
            
            if (! $resultNewDocsVers) {
                throw new Exception(
                    json_encode([
                        'status' => self::$status['failed'],
                        'retryable' => true,
                        'message' => 'Failed to create document version',
                        'exception' => 'document_version.create.failed',
                    ])
                );
            }
            
            Log::channel('library_log_documents')
            ->info('[Success] Save new data documents', [
                'param' => [
                    'file' => $fileMeta, 
                    'original' => $originalName, 
                    'owner' => $ownerId
                ],
                'data' => [
                    'id_document' => $uuidDocuments,
                    'id_collab' => $uuidDocsCollabs,
                    'id_version' => $uuidDocsVersion,
                ],
            ]);
            
            
            return json_decode(json_encode(
                [
                    'status' => true,
                    'message' => '',
                    'data' => [
                        'id_document' => $uuidDocuments,
                        'id_collab' => $uuidDocsCollabs,
                        'id_version' => $uuidDocsVersion,
                    ],
                ]
            ));
            
            
            
        } catch(QueryException $e) {
            
            Log::debug('Query Error', [
                'binding' => $e->getBindings(),
                'errorInfo' => $e->errorInfo,
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'connection' => $e->getConnectionName(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'previous' => json_encode($e->getPrevious()),
                'sql' => $e->getSql(),
                'trace' => json_encode($e->getTrace()),
            ]);
            
            $payload = json_decode($e->getMessage());
            $trace = $e->getTrace();
            return (object) [
                'status' => false,
                'payload' => $payload,
                'exception' => $trace,
            ];
            
        } catch (Exception $e) {
            
            $payload = json_decode($e->getMessage());
            $trace = $e->getTrace();
            $message = $e->getMessage();
            
            Log::channel('library_log_documents')
                ->info('[Error] Error insert new data', [
                    'payload' => $payload,
                    'trace' =>  $trace,
                ]);
            self::rollbackDocuments($uuidDocuments);
            
            return (object) [
                'status' => false,
                'payload' => $payload,
                'exception' => $trace,
            ];
        }
    }
     
    /**
     * Summary of saveNewFileDocument
     * @param string|Uuid $ownerId
     * @var array $fileMeta originalName, originalExt, mime, size, realPath, relativePath, basename
     * @return object
     */
    public static function saveNewDataFilesDocuments($ownerId, $fileMeta) {
        self::loadConfig();
        
        try {
            
            // 'originalName' => $file->getClientOriginalName(),
            // 'originalExt' => $file->getClientOriginalExtension(),
            // 'mime' => $file->getMimeType(),
            // 'size' => $file->getSize(),
            // 'realPath' => $realPath,
            // 'relativePath' => LibHelper::normalizePath($relativePath),
            // 'basename' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            
            if (! self::checkFileMeta($fileMeta)) {
                throw new Exception('Invalid file metadata');
            }
            
            if (!in_array($fileMeta['mime'], self::$config['accept'])) {
                throw new Exception('Unsupported MIME type: ' . $fileMeta['mime']);
            }
            
            $source = [
                'disk' => config('chunk-upload.storage.disk'),
                'path' => $fileMeta['relativePath'],
            ];
            
            $originalMimeType = FileHelper::getMimeType($source['disk'], $source['path']);
            if (! $originalMimeType) {
                throw new Exception("mime type undefined");
            }
            
            if ($originalMimeType != $fileMeta['mime']) {
                throw new Exception('Diffent value');
            }
            
            $filename = LibHelper::generateUniqueString(32);
            $ext = MimeTypes::getDefault()
                ->getExtensions($fileMeta['mime'])[0];
            
            $filenameExt = $filename . '.' . $ext;
            $filePath = $ownerId . '/' . $filenameExt;
            
            $target = [
                'disk' => 'documents',
                'path' => $filePath,
            ];
            
            if (! FileHelper::copyFile($source, $target)) {
                throw new Exception('something error when copying file');
            }
            
            $sizeFile = Storage::disk($target['disk'])->size($target['path']);
            
            $uuidFileDocument = LibHelper::generateUniqueUuId('v7', 'id_file_document', FilesMod\FilesDocuments::class);
            $resultSaveFile = FilesMod\FilesDocuments::create([
                'id_file_document' => $uuidFileDocument,
                'owner_id' => $ownerId,
                'file_name' => $filename,
                'file_ext' => $ext,
                'file_path' => $filePath,
                'file_mime' => $fileMeta['mime'],
                'file_size' => $sizeFile,
            ]);
            if (! $resultSaveFile) {
                throw new Exception('');
            }
            
            
            return (object) [
                'status' => true,
                'message' => 'success save',
                'id_file_document' => $uuidFileDocument,
            ];
            
        } catch (Exception $e) {
            
            return (object) [
                'status' => false,
                'message' => '...',
                'id_file_document' => null,
                'exception' => $e->getTrace(),
            ];
        }
        
    }
    
    /**
     * Summary of saveNewDocumentSignatures
     * @param string|Uuid $id_document
     * @param string|Uuid $id_document_collaborator
     * @return object
     */
    public static function saveNewDataDocumentSignatures($id_document, $id_document_collaborator) {
        
        try {
            
            $uuidDocsSigs = LibHelper::generateUniqueUuId('v4', 'id_document_signature', Documents\Signatures::class);
            $resultDocsSigs = Documents\Signatures::create([
                'id_document_signature' => $uuidDocsSigs,
                'id_document' => $id_document,
            ]);
            
            if (! $resultDocsSigs) {
                throw new Exception('');
            }
            
            $resultDocsSigsType = Documents\SignaturesType::create([
                'id_document_signature' => $uuidDocsSigs,
            ]);
            if (! $resultDocsSigsType) {
                throw new Exception('');
            }
            
            $resultDocsSigsStatus = Documents\SignaturesStatus::create([
                'id_document_signature' => $uuidDocsSigs,
            ]);
            if (! $resultDocsSigsStatus) {
                throw new Exception('');
            }
            
            $uuidDocsSigsSigner = LibHelper::generateUniqueUuId('v7', 'id_document_signature_signer', Documents\SignaturesSigner::class);
            $resultDocsSigsSigner = Documents\SignaturesSigner::create([
                'id_document_signature_signer' => $uuidDocsSigsSigner,
                'id_document_signature' => $uuidDocsSigs,
                'id_document_collaborator' => $id_document_collaborator,
            ]);
            if (! $resultDocsSigsSigner) {
                throw new Exception('');
            }
            
            $listPermission = ['validate', 'finalize', 'sign'];
            $permissionOrder = [];
            foreach ($listPermission as $index => $permission) {
                $uuidDocsSigsPermission = LibHelper::generateUniqueUuId('v7', 'id_document_signature_permission', Documents\SignaturesPermission::class);
                $resultDocsSigsPermission = Documents\SignaturesPermission::create([
                    'id_document_signature_permission' => $uuidDocsSigsPermission,
                    'id_document_signature' => $uuidDocsSigs,
                    'id_document_collaborator' => $id_document_collaborator,
                    
                    'permission' => $permission,
                ]);
                if (!$resultDocsSigsPermission) {
                    throw new Exception('');
                }
                
                $permissionOrder[] = [
                    'id_document_signature_permission' => $uuidDocsSigsPermission,
                    'order' => $index,
                ];
            }
            
            $resultDocsSigsSignerOrder = Documents\SignaturesSignerOrder::create([
                'id_document_signature' => $uuidDocsSigs,
                'order_sign' => $permissionOrder
            ]);
            if (! $resultDocsSigsSignerOrder) {
                throw new Exception('');
            }
            
            
            return (object) [
                'status' => true,
                'message' => '',
            ];
            
        } catch (Exception $e) {
            
            
            return (object) [
                'status' => false,
                'message' => ''
            ];
            
            
        }
        
    }
    
    
    
    public static function rollbackDocuments($id_document) {
        
    }
    
    /**
     * Summary of checkFileMeta
     * @param array $fileMeta
     * @return bool
     */
    // private static function checkFileMeta($fileMeta) {
    //     $checkedMeta = collect($fileMeta)->filter(function($value, $key) {
    //         return in_array($key, self::$mapFileMeta);
    //     });
        
    //     return count($checkedMeta) == count(self::$mapFileMeta);
    // }
    
    private static function checkFileMeta($fileMeta) {
        return count(array_intersect_key($fileMeta, array_flip(self::$mapFileMeta))) === count(self::$mapFileMeta);
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