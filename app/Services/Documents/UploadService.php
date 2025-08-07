<?php

namespace App\Services\Documents;

use App\Utils\ChunkUtils;
use App\Trait\HasMeta;

use App\Utils\ModelUtils;
use App\Utils\RequestUtils;

use App\Services\Support\UniqueValueGenerator;
use App\Services\Support\Documents\ServicesSupport;
use App\Services\Support\Documents\Enum\JobProcessType as JobType;

use App\Jobs\Documents\Micros\SaveMain;
use App\Libraries\ArrayHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;

use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\ResumableJSUploadHandler;
 
use Carbon\Carbon;

use InvalidArgumentException;
use RuntimeException;
use Throwable;

class UploadService {
    
    private const JOB_RPOCESS_MODEL = \App\Models\Jobs\Documents\Process::class;
    
    protected Request $request;
    protected UploadedFile $file;
    
    public function __construct(Request $request) {
        $this->request = $request;
    }
    
    
    public static function handle(Request $request) {
        return (new static($request))->execute();
    }
    
    
    public function execute() {
        
        $result = RequestUtils::createJsonResponse(
            [
                'status' => 'error',
                'message' => '',
            ], 400
        );
        
        
        try {
            
            if (! ServicesSupport::checkAccept($this->request->resumableType)) {
                throw new InvalidArgumentException('File type is not supported. Please upload a document in an accepted format.');
            }
            if (! ServicesSupport::checkLimit($this->request->resumableTotalSize)) {
                throw new InvalidArgumentException('File size exceeds the allowed limit. Please upload a smaller file.');
            }
            
            $receiver = new FileReceiver('file', $this->request, ResumableJSUploadHandler::class);
            
            if ($receiver->isUploaded() === false) {
                throw new RuntimeException('File upload failed. Please try again or check your network connection.');
            }
            
            $save = $receiver->receive();
            
            if ($save->isFinished()) {
                $file = $save->getFile();
                $this->file = $file;
                $this->save();
                // static::saveNewDocuments($file, $this->request);
                
                // testing save
                $file->storeAs('uploads/documents', $file->getClientOriginalName(), 'local');
                // save
                
                $result = RequestUtils::createJsonResponse([
                    'status' => 'success',
                    'processing' => true,
                    'message' => 'File upload completed successfully. Processing will begin shortly.',
                ], 200);
            } else {
                
                $handler = $save->handler();
                
                $result = RequestUtils::createJsonResponse([
                    'status' => 'process',
                    'upload' => true,
                    'message' => 'File upload in progress.',
                    'percentage' => $handler->getPercentageDone()
                ], 200);
            }
            
            
        } catch(InvalidArgumentException $e) {
            $result = RequestUtils::createJsonResponse([
                'status' => 'error',
                'processing' => false,
                'message' => $e->getMessage()
            ], 400);
            
        } catch(RuntimeException $e) {
            $result = RequestUtils::createJsonResponse([
                'status' => 'error',
                'processing' => false,
                'message' => $e->getMessage()
            ], 500);
            
        } catch (Throwable $e) {
            
            $result = RequestUtils::createJsonResponse([
                'status' => 'error',
                'processing' => false,
                'message' => 'An unexpected error occurred during file upload. Please try again later or contact support.'
            ], 500);
        }
        
        
        return $result;
        
    }
    
    
    
    
    public function save() {
        if (! $this->file) {
            throw new InvalidArgumentException("");
        }
        
        if (! ServicesSupport::checkAccept($this->file->getMimeType())) { 
            throw new InvalidArgumentException('File type is not supported. Please upload a document in an accepted format.');
        }
        if (! ServicesSupport::checkLimit($this->file->getSize())) {
            throw new InvalidArgumentException('File size exceeds the allowed limit. Please upload a smaller file.');
        }
        
        $instanceJobProcessModel = ModelUtils::createInstanceModel(static::JOB_RPOCESS_MODEL);
        
        $ownerId = Auth::user()->id_user;
        $sessionId = session()->getId();
        
        $fileMetadata = ChunkUtils::getChunkMetadataFile($this->file->getFilename());
        $requestData = collect($this->request->all())
            ->filter(function($value, $key) {
                return  !($value instanceof UploadedFile) && 
                        !str_starts_with($key, 'resumable');
            })
            ->toArray();
        
        $id_job_process = ModelUtils::generateNewUuid(static::JOB_RPOCESS_MODEL);
        $token_job_process = UniqueValueGenerator::stringByIlluminate(
            32, 
            'token', 
            $instanceJobProcessModel->getTable(), 
            $instanceJobProcessModel->getConnectionName()
        );
        
        // 'file_meta', 'request', 'owner_id', 'session_id', 'id_document', 'id_document_collaborator', 'id_document_file', 'id_document_versions', 'id_document_signatures', 'id_document_signatures_permission', 'id_document_signatures_signer', 
        $payload = [
            'file_meta' => $fileMetadata,
            'request' => $requestData,
            'owner_id' => $ownerId,
            'session_id' => $sessionId,
            'group_ids' => [],
        ];
        ArrayHelper::ksort_recursive($payload);
        // $integrityHash = hash('sha256', json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
        // $integrityId = hash('sha256', json_encode([], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
        
        // $payload['integrity_hash'] = $integrityHash;
        // $payload['integrity_id'] = $integrityId;
        
        $process = [
            'attempt' => 1,
            'step' => 'Initialization',
            'actor' => 'system',
            'status' => JobType::CREATE->value,
            'message' => 'The job has been queued and is waiting to be processed.',
            'exception' => null,
            'timestamp' => Carbon::now()->toIso8601String(),
        ];
        
        $jobProcessRecord = $instanceJobProcessModel->create([
            'id_app_jobs_process_docs' => $id_job_process,
            'id_user' => $ownerId,
            'token' => $token_job_process,
            'type_job' => JobType::CREATE->value,
            'payload' => $payload,
            'message' => 'Create job queued successfully. Processing will begin shortly.',
            'attempts' => 0,
            'process_detail' => [ $process ],
            'expire_at' => Carbon::now()->addDays(3),
        ]);
        
        // job dispatch
        
        dispatch(new SaveMain($id_job_process));
        
        
    }
    
    
    
    
    
    
}