<?php

namespace App\Jobs\Documents\Micros;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use Illuminate\Database\Eloquent\Model;

use App\Libraries\ArrayHelper;
use App\Trait\HasMeta;

use App\Utils\ModelUtils;
use App\Utils\QueryExceptionUtils;


use App\Services\Support\JobServiceSupport;

use App\Services\Support\Documents\ServicesSupport;
use App\Services\Support\Documents\JobProcessSupport;

use App\Enums\Jobs\Documents\ProcessState;
use App\Enums\Jobs\Documents\ProcessStatus;
use App\Enums\Jobs\Documents\ProcessType;

use App\Enums\Documents\Role;
use App\Enums\Documents\Publicity;
use App\Enums\Documents\Signature\ExpiredAction;
use App\Enums\Documents\Signature\Permission;
use App\Enums\Documents\Signature\Status;
use App\Enums\Documents\Signature\Type;

use App\Jobs\Documents\Micros\SaveMetaFile;


use App\Exceptions\Jobs\Documents\JobProcessException;
use App\Utils\LogUtils;
use Throwable;

class SaveMain implements ShouldQueue
{
    use Queueable, HasMeta;
    
    // 'file_meta' => $fileMetadata,
    // 'request' => $requestData,
    // 'owner_id' => $ownerId,
    // 'session_id' => $sessionId
    private const PROCESS_TABLE = 'app_jobs_process_docs';
    private const ORIGINAL_PAYLOAD_KEYS  = [
        'file_meta', 
        'request', 
        'owner_id', 
        'session_id',
        'group_ids'
    ];
    
    private const GENERATED_ID_KEYS  = [
        'id_document', 
        'id_document_collaborator', 
        'id_document_file', 
        'id_document_version', 
        'id_document_signatures', 
        'id_document_signatures_permission', 
        'id_document_signatures_signer'
    ];
    
    protected string $id_proc_job;
    protected array $rollback = [];
    
    
    public $tries = 3; 
    
    /**
     * Create a new job instance.
     */
    public function __construct(string $id_proc_job)
    {
        JobServiceSupport::log(
            'Construct job: ' . static::class,
            [
                'job_class' => static::class,
                'job_process_table' => \App\Models\Jobs\Documents\Process::class,
                'job_process_id' => $id_proc_job,
                'method' => __METHOD__,
                'function' => __FUNCTION__
            ]
        );
        $this->id_proc_job = $id_proc_job;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->job->attempts() < 2) {
            JobServiceSupport::log(
                'Handle job: ' . static::class,
                [
                    'job_class' => static::class,
                    'job_process_table' => \App\Models\Jobs\Documents\Process::class,
                    'job_process_id' => $this->id_proc_job,
                    'method' => __METHOD__,
                    'function' => __FUNCTION__
                ]
            );
            JobProcessSupport::log(
                'Handle job: ' . static::class,
                [
                    'job' => static::class,
                    'id_process' => $this->id_proc_job,
                    'method' => __METHOD__,
                    'function' => __FUNCTION__
                ]
            );
        }
        $this->execute();
    }
    
    
    private function execute() {
        
        $message = 'Retrying Execute job: ' . static::class;
        if ($this->job->attempts() < 2) {
            $message = 'Execute job: ' . static::class;
        }
        
        JobServiceSupport::log(
            $message,
            [
                'job_class' => static::class,
                'job_process_table' => \App\Models\Jobs\Documents\Process::class,
                'job_process_id' => $this->id_proc_job,
                'method' => __METHOD__,
                'function' => __FUNCTION__
            ]
        );
        
        JobProcessSupport::log(
            $message,
            [
                'job' => static::class,
                'id_process' => $this->id_proc_job,
                'method' => __METHOD__,
                'function' => __FUNCTION__,
            ]
        );
        
        $jobProcessModel = ModelUtils::createInstanceModel(static::PROCESS_TABLE);
        $jobProcessModelName = get_class($jobProcessModel);
        $jobProcessRecord = null;
        $jobProcessArray = [];
        
        try {
            
            $jobProcessRecord = $jobProcessModel
                ->query()
                ->find($this->id_proc_job);
            
            $jobProcessArray = JobProcessSupport::validateJobProcess($jobProcessRecord);
            $payload = $jobProcessArray['payload'];
            
            // $originalIntegrityHash = $payload['integrity_hash'];
            // $originalIntegrityId = $payload['integrity_id'];
            $groupIdsFromPayload = $payload['group_ids'];
            
            $file_meta = $payload['file_meta'];
            $requestData = $payload['request'];
            $owner_id = $payload['owner_id'];
            $session_id = $payload['session_id'];
            
            // --- Generate UUIDs for Main Document Models
            $id_document = ModelUtils::generateNewUuid('document');
            $id_document_collaborator = ModelUtils::generateNewUuid('document_collaborator');
            $id_document_file = ModelUtils::generateNewUuid('document_file'); // File ID juga digenerate di sini
            $id_document_version = ModelUtils::generateNewUuid('document_versions');
            $id_document_signatures = ModelUtils::generateNewUuid('document_signatures');
            $id_document_signatures_permission = ModelUtils::generateNewUuid('document_signatures_permission');
            $id_document_signatures_signer = ModelUtils::generateNewUuid('document_signatures_signer');
            
            $originalName = pathinfo($payload['request']['originalFilename'], PATHINFO_FILENAME);
            $nameVersion = 1;
            $resultCheckName = ModelUtils::createInstanceQuery('document')
                ->where('documents.owner_id', '=', $payload['owner_id'])
                ->join('documents_information', 'documents_information.id_document', '=', 'documents.id_document')
                ->where('documents_information.name', '=', $originalName);
            
            if ($resultCheckName->exists()) {
                $lastVersion = $resultCheckName->latest('name_version')->first();
                $nameVersion = (int) $lastVersion->name_version + 1;
            }
            
            
            // --- Process Insert New Data
            $this->rollback[] = ModelUtils::create('document', [
                'id_document' => $id_document,
                'owner_id' => $owner_id
            ]);
            
            $this->rollback[] = ModelUtils::create('document_information', [
                'id_document' => $id_document,
                'name' => $originalName,
                'name_version' => $nameVersion,
            ]);
            
            $this->rollback[] = ModelUtils::create('document_publicity', [
                'id_document' => $id_document,
                'status_publicity' => Publicity::PRIVATE->value,
            ]);
            
            // $this->rollback[] =  ModelUtils::create('document_collaborator', [
            //     'id_document_collaborator' => $id_document_collaborator,
            //     'id_user' => $owner_id,
            //     'id_document' => $id_document,
            //     'role' => Role::SIGNER->value,
            // ]);
            
            $this->rollback[] =  ModelUtils::create('document_versions', [
                'id_document_version' => $id_document_version,
                'id_document' => $id_document,
                'version' => 1,
            ]);
            
            $generatedIds = [
                'id_document' => $id_document,
                'id_document_collaborator' => $id_document_collaborator,
                'id_document_file' => $id_document_file,
                'id_document_version' => $id_document_version,
                'id_document_signatures' => $id_document_signatures,
                'id_document_signatures_permission' => $id_document_signatures_permission,
                'id_document_signatures_signer' => $id_document_signatures_signer,
            ];
            
            $payload['group_ids'] = array_merge($groupIdsFromPayload, $generatedIds);
            ArrayHelper::ksort_recursive($payload);
            
            LogUtils::log('jobs_documents', 'payload', $payload);
            
            // $newIntegrityHash = hash('sha256', json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
            // $newIntegrityIdHash = hash('sha256', json_encode($payload['group_ids'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
            // $payload['integrity_hash'] = $newIntegrityHash;
            // $payload['integrity_id'] = $newIntegrityIdHash;
            LogUtils::log('jobs_documents', 'payload', $payload);
            
            JobProcessSupport::updateJobProcess(
                $jobProcessRecord,
                ProcessStatus::PROCESS,
                ProcessState::PROCESSABLE,
                $payload,
                'Master document records successfully created.',
                [
                    ['message' => 'Step 1/X: Master document record creation successful.', 'status' => ProcessStatus::COMPLETED],
                ],
                null,
                false,
                [],
                'Create Master Document Records',
                ProcessStatus::COMPLETED->value,
                'Document master records and relationships saved successfully.'
            );
            
            JobProcessSupport::log(
                'Success update job '. static::class,
                [
                    'job' => static::class,
                    'id_process' => $this->id_proc_job,
                    'method' => __METHOD__,
                    'function' => __FUNCTION__,
                ],
                'info',
                'success_step'
            );
            
            
            dispatch(new SaveMetaFile($this->id_proc_job));
            
        } catch (QueryException $e) {
            
            $this->rollback(); 
            $utils = QueryExceptionUtils::handle($e->getConnectionName(), $e);
            $jobAttemp = $this->job->attempts();
            
            if ($utils->shouldRetry() && $jobAttemp < $this->tries) {
                
                JobProcessSupport::handleExceptionJobProcess(
                    static::class,
                    $jobProcessRecord,
                    ProcessState::PROCESSABLE,
                    "Temporary database error while inserting master document records. Retrying...",
                    "Retryable DB issue on master record insert. Releasing job for retry.",
                    $e,
                    'query_retryable',
                    'Create Master Document Records',
                    'Retryable exception during master document inserts.'
                );
                
                $this->release(5);
            } else {
                JobProcessSupport::handleExceptionJobProcess(
                    static::class,
                    $jobProcessRecord,
                    ProcessState::BLOCKED,
                    "Database error. Master document creation failed.",
                    "Fatal DB error encountered on master record step.",
                    $e,
                    'query_exception',
                    'Create Master Document Records',
                    'Fatal exception during document master record creation.'
                );
            }
            
            
        }  catch(JobProcessException $e) {
            $this->rollback(); 
            
            $message = $e->getMessage();
            
            JobProcessSupport::handleExceptionJobProcess(
                static::class,
                $jobProcessRecord,
                $e->state,
                "Validation failed for master document input.",
                "Missing or invalid input fields. Master step blocked.",
                $e,
                'job_process_exception',
                'Create Master Document Records',
                'Job stopped due to invalid or incomplete master record input.'
            );
            
            if ($e->state === ProcessState::BLOCKED) {
                $this->fail($e);
            } else {
                $this->release(5);
            }
            
            
        } catch (Throwable $e) {
            $this->rollback();
            
            JobProcessSupport::handleExceptionJobProcess(
                static::class,
                $jobProcessRecord,
                ProcessState::BLOCKED,
                "Unhandled system error during master document creation.",
                "Unexpected fatal error during master document processing.",
                $e,
                'fatal_throwable',
                'Create Master Document Records',
                'Unexpected exception occurred in SaveMain job.'
            );
            
            $this->fail($e);
            
        }
        
        JobServiceSupport::log(
            'End job: ' . static::class,
            [
                'job_class' => static::class,
                'job_process_table' => \App\Models\Jobs\Documents\Process::class,
                'job_process_id' => $this->id_proc_job,
                'method' => __METHOD__,
                'function' => __FUNCTION__
            ],
            'info',
            'done'
        );
        
        return;
    }
    
    
    private function rollback() {
        if (empty($this->rollback)) return;
        
        foreach($this->rollback as $model) {
            if (! ($model instanceof Model)) {
                continue;
            }
            
            $modelClass = get_class($model);
            $changes = collect($model->getChanges())->toArray();
            try {
                $model->forceDelete();
                
                JobProcessSupport::log(
                    'Rollback successful for model: ' . $modelClass,
                    [
                        'model' => $modelClass,
                        'changes' => $changes,
                        'status' => 'deleted'
                    ],
                    'alert'
                );
                
            } catch(ModelNotFoundException $e) {
                JobProcessSupport::log(
                    'Rollback skipped: model not found (may be already deleted via cascade): ' . $modelClass,
                    [
                        'model' => $modelClass,
                        'changes' => $changes,
                        'status' => 'not_found'
                    ],
                    'alert'
                );
                
            } catch (Throwable $e) {
                JobProcessSupport::log(
                    'Rollback failed for model: ' . $modelClass,
                    [
                        'model' => $modelClass,
                        'changes' => $changes,
                        'status' => 'error'
                    ],
                    'critical'
                );
            }
        }
        
    }
    
    
    
    
}
