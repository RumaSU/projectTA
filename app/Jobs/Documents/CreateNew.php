<?php

namespace App\Jobs\Documents;

// use Illuminate\Bus\Queueable;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Queue\Queueable;

use Illuminate\Support\Facades\Log;

use Ramsey\Uuid\Uuid;

use App\Models\Documents;
use App\Models\Jobs\Documents\Process;

use App\Library\Helper as LibHelper;
use App\Library\Documents\NewData;

use App\Events\Documents\ProcessNewDocument;
use App\Events\Documents\Now\StatusUpload;
use App\Events\Documents\Now\ProcessNew;

use Carbon\Carbon;


class CreateNew implements ShouldQueue
{
    use Queueable;
    
    /** @var array */
    protected $fileMeta, $requestData;
    /** @var string */
    protected $ownerId, $sessionId;
    
    
    /**
     * Create a new job instance.
     * @param array $fileMeta ['originalName' => '', 'originalExt' => '', 'mime' => '', 'size' => '', 'realPath' => '', 'relativePath' => '', 'basename' => '']
     * @param array $request 
     * @param string $ownerId 
     * @param string $sessionId 
     * 
     * @param array $newId
     * 
     */
    public function __construct($fileMeta, $requestData, $ownerId, $sessionId)
    {
        $this->fileMeta = $fileMeta;
        $this->requestData = $requestData;
        $this->ownerId = $ownerId;
        $this->sessionId = $sessionId;
        Log::channel('jobs_log')->info('[NEW] Constructor create new documents log', ['storage' => storage_path('logs/jobs/documents/docs.log')]);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 'originalName' => $file->getClientOriginalName(),
        // 'originalExt' => $file->getClientOriginalExtension(),
        // 'mime' => $file->getMimeType(),
        // 'size' => $file->getSize(),
        // 'realPath' => $realPath,
        // 'relativePath' => LibHelper::normalizePath($relativePath),
        // 'basename' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
        
        // "resumableChunkNumber",
        // "resumableChunkSize",
        // "resumableCurrentChunkSize",
        // "resumableTotalSize",
        // "resumableType",
        // "resumableIdentifier",
        // "resumableFilename",
        // "resumableRelativePath",
        // "resumableTotalChunks",
        // "_token",
        // "token_resumable",
        // "token_upload",
        // "originalFileName",
        
        // 'id_app_jobs_process_docs',
        // 'id_user',
        // 'token',
        // 'payload',
        // 'message',
        // 'retry_reason',
        // 'retryable',
        // 'attempts',
        // 'exception',
        
        
        
        
        
        
        
        $uuidProcess = LibHelper::generateUniqueUuId('v7');
        $tokenProcess = LibHelper::generateUniqueString(12, null, null, 'DOC_PR');
        $processJob = Process::create([
            'id_app_jobs_process_docs' => $uuidProcess,
            'id_user' => $this->ownerId,
            'token' => $tokenProcess,
            'payload' => (object) [
                'file' => $this->fileMeta, 
                'request' => $this->requestData, 
                'sessionId' => $this->sessionId, 
                'ownerId' => $this->ownerId
            ],
            
            // 'message',
            // 'retry_reason',
            // 'retryable',
            'attempts' => 1,
            'is_cancelled' => true,
            // 'exception',
            'expire_at' => Carbon::now()->addHours(3),
        ]);
        
        Log::channel('jobs_documents')
            ->info(
                '[CREATE] Create new documents', 
                [
                    'file' => $this->fileMeta, 
                    'request' => $this->requestData, 
                    'sessionId' => $this->sessionId, 
                    'ownerId' => $this->ownerId,
                    'jobId' => $this->job->getJobId(),
                    'jobPayload' => $this->job->payload(),
                ]
            );
        
        $stepProcess = [
            'main' => false,
            'file' => false,
            'signature' => false,
        ];
        $payloadProcess = [
            'message' => 'Process new documents',
            'token_process' => $tokenProcess,
            'step' => &$stepProcess,
            'status' => 'process',
            'new' => true,
        ];
        
        try {
            $processJob->refresh();
            Log::channel('jobs_documents')
                ->info(
                    '[Process] Process new documents [0 / 3]', 
                    ['file' => $this->fileMeta, 'request' => $this->requestData, 'sessionId' => $this->sessionId, 'ownerId' => $this->ownerId]
                );
            
            $this->broadcastProcess($payloadProcess);
            
            $tempOriginalName = LibHelper::generateUniqueString(12);
            $resultMainData = NewData::saveNewDataDocuments($this->fileMeta, $this->requestData['originalFileName'] ?? $tempOriginalName, $this->ownerId);
            if (! $resultMainData->status) {
                throw new Exception(json_encode($resultMainData));
            }
            
            $processJob->refresh();
            
            $stepProcess['main'] = true;
            $payloadProcess['message'] = 'Process saving documents';
            $this->broadcastProcess($payloadProcess);
            
            Log::channel('jobs_documents')
                ->info(
                    '[Process] Process new documents [1 / 3]', 
                    [
                        'file' => $this->fileMeta, 
                        'request' => $this->requestData, 
                        'sessionId' => $this->sessionId, 
                        'ownerId' => $this->ownerId,
                        'resultMain' => $resultMainData,
                    ]
                );
            
            // $resultSaveFile = NewData::saveNewDataFilesDocuments($this->ownerId, $this->fileMeta);
            
            
            
            
            
            
        } catch(Exception $e) {
            // broadcast(new \App\Events\Documents\ProcessNewDocument('', [''], $this->sessionId, $this->resumableId));
            $objCatch = json_decode($e->getMessage());
            
            
            
            Log::channel('jobs_documents')
                ->info('[Error] process new documents', [
                    'file' => $this->fileMeta, 
                    'request' => $this->requestData, 
                    'sessionId' => $this->sessionId, 
                    'ownerId' => $this->ownerId,
                ]);
            
            
            Log::channel('jobs_documents')
                ->info('[Error] Error when process new documents', [
                    'file' => $this->fileMeta, 
                    'request' => $this->requestData, 
                    'sessionId' => $this->sessionId, 
                    'ownerId' => $this->ownerId, 
                    'error' => $e->getTrace(), 
                    'errorObj' => $objCatch
                ]);
            
            // $processJob->update([
            //     'message' => $objCatch->message,
            //     'retry_reason' => $objCatch->message,
            //     'retryable' => $objCatch->retryable,
            //     'is_cancelled' => false,
            //     'exception' => $objCatch->exception,
            // ]);
            
            // $payloadProcess['status'] = 'failed';
            
            
            return;
            
        }
        
    }
    
    
    protected function broadcastProcess($payload) {
        broadcast(
            new ProcessNew(
                $payload,
                $this->sessionId, 
                $this->requestData['token_resumable'],
            )
        );
    }
    
}
