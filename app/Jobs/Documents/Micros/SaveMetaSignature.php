<?php

namespace App\Jobs\Documents\Micros;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use Illuminate\Database\QueryException;

use App\Libraries\ArrayHelper;

use App\Utils\StorageUtils;
use App\Utils\ModelUtils;
use App\Utils\QueryExceptionUtils;
use App\Utils\ChunkUtils;


use App\Services\Support\JobServiceSupport;

use App\Services\Support\Documents\JobProcessSupport;
use App\Services\Support\FileDiskSupport;

use App\Enums\Jobs\Documents\ProcessState;
use App\Enums\Jobs\Documents\ProcessStatus;
use App\Enums\Files\Entity;
use App\Enums\Hash;

use App\Enums\Audit\Documents\Category;
use App\Enums\Audit\Documents\Event;

use App\Enums\Documents\Role;
use App\Enums\Documents\Publicity;
use App\Enums\Documents\Signature\ExpiredAction;
use App\Enums\Documents\Signature\Permission;
use App\Enums\Documents\Signature\Status;
use App\Enums\Documents\Signature\Type;

use App\Exceptions\Jobs\Documents\JobProcessException;
use App\Services\Support\UniqueValueGenerator;
use Carbon\Carbon;
use Symfony\Component\Mime\MimeTypes;
use Throwable;

class SaveMetaSignature implements ShouldQueue
{
    use Queueable;
    
    
    
    private const PROCESS_TABLE = 'app_jobs_process_docs';
    private const ORIGINAL_PAYLOAD_KEYS  = [
        'file_meta', 
        'request', 
        'owner_id', 
        'session_id',
        'group_ids'
    ];
    
    private const GROUP_MAIN_ID_KEYS  = [
        'id_document', 
        'id_document_collaborator', 
        'id_document_file', 
        'id_document_version', 
        'id_document_signatures', 
        'id_document_signatures_permission', 
        'id_document_signatures_signer'
    ];
    
    private const GROUP_FILE_ID_KEYS = [
        'id_file_disk',
        'id_file_disk_entity',
        'id_file_disk_token',
        'id_file_document'
    ];
    
    protected string $id_proc_job;
    
    protected string $id_document;
    protected string $id_document_signature;
    protected string $fullpath;
    protected array $group_ids;
    protected array $rollback = [];
    
    protected ProcessState $processState;
    
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
            
            $file_meta = $payload['file_meta'];
            $requestData = $payload['request'];
            $owner_id = $payload['owner_id'];
            $session_id = $payload['session_id'];
            $group_ids = $payload['group_ids'];
            
            
            if (! ArrayHelper::key_exists(static::GROUP_MAIN_ID_KEYS, $group_ids)) {
                throw new JobProcessException(
                    ProcessStatus::FAILED,
                    ProcessState::BLOCKED,
                    "Missing required document group identifiers"
                );
            }
            
            $this->id_document = $group_ids['id_document'];
            
            if (! ArrayHelper::key_exists(static::GROUP_FILE_ID_KEYS, $group_ids)) {
                throw new JobProcessException(
                    ProcessStatus::FAILED,
                    ProcessState::BLOCKED,
                    "Missing required file group identifiers"
                );
            }
            
            $this->group_ids = $group_ids;
            
            $id_document_signature = ModelUtils::generateNewUuid(\App\Models\Documents\Signatures::class);
            ModelUtils::create(
                \App\Models\Documents\Signatures::class, 
                [
                    'id_document_signature' => $id_document_signature,
                    'id_document' => $this->id_document
                ]
            );
            $this->id_document_signature = $id_document_signature;
            
            ModelUtils::create(
                \App\Models\Documents\SignaturesType::class, 
                [
                    'id_document_signature' => $id_document_signature,
                    'type' => Type::UNCATEGORIZED->value,
                    'type_changed' => Carbon::now()
                ]
            );
            
            ModelUtils::create(
                \App\Models\Documents\SignaturesStatus::class, 
                [
                    'id_document_signature' => $id_document_signature,
                    'status' => Status::DRAFT->value,
                    'status_changed' => Carbon::now()
                ]
            );
            
            
            JobProcessSupport::updateJobProcess(
                $jobProcessRecord,
                ProcessStatus::COMPLETED,
                ProcessState::BLOCKED,
                $payload,
                'Initial signature metadata successfully created.',
                [
                    ['message' => 'Signature master record created.', 'status' => ProcessStatus::PROCESS],
                    ['message' => 'Step Final: Signature meta created successfully.', 'status' => ProcessStatus::COMPLETED],
                ],
                null,
                false,
                [],
                'Create Signature Meta',
                ProcessStatus::COMPLETED->value,
                'Signature ID, type, and status have been initialized.'
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
            
            $payload = [
                'id_document_audit_log' => ModelUtils::generateNewUuid(\App\Models\Audit\Document::class),
                'id_document' => $group_ids['id_document'],
                'id_user' => $owner_id,
                'category' => Category::DOCUMENT->value,
                'event_type' => Event::CREATED->value,
                'description' => 'Document created',
                'metadata' => null,
                'logged_at' => Carbon::now(),
            ];
            
            ksort($payload);
            $payload['data_hash'] = Hash::SHA256->hash(json_encode($payload));
            
            ModelUtils::create(
                \App\Models\Audit\Document::class,
                $payload
            );
            
            
        } catch (QueryException $e) {
            
            $utils = QueryExceptionUtils::handle($e->getConnectionName(), $e);
            $jobAttemp = $this->job->attempts();
            
            if ($utils->shouldRetry() && $jobAttemp < $this->tries) {
                $this->processState = ProcessState::PROCESSABLE;
                
                $this->rollback(); 
                
                JobProcessSupport::handleExceptionJobProcess(
                    static::class,
                    $jobProcessRecord,
                    ProcessState::PROCESSABLE,
                    "A temporary database issue occurred. The system will retry this process shortly.",
                    "Retryable DB error detected. Job released for retry.",
                    $e,
                    'query_retryable',
                    'Database Insert Logic',
                    'A retryable exception was thrown during database insert operations.'
                );
                
                $this->release(5);
            } else {
                $this->processState = ProcessState::BLOCKED;
                
                $this->rollback(); 
                
                JobProcessSupport::handleExceptionJobProcess(
                    static::class,
                    $jobProcessRecord,
                    ProcessState::BLOCKED,
                    "A database error occurred. Document creation could not proceed.",
                    "Fatal database error encountered, job cannot continue.",
                    $e,
                    'query_exception',
                    'DB Insertion',
                    'Fatal database error encountered, job cannot continue.'
                );
            }
            
            
        }  catch(JobProcessException $e) {
            
            $message = $e->getMessage();
            $this->processState = $e->state;
            $this->rollback(); 
            
            JobProcessSupport::handleExceptionJobProcess(
                static::class,
                $jobProcessRecord,
                $e->state,
                "The process has been stopped due to a blocking condition.",
                "Blocked due to missing or invalid job prerequisites.",
                $e,
                'job_process_exception',
                "Validation",
                "Job process aborted due to unmet conditions or invalid payload."
            );
            
            if ($e->state === ProcessState::BLOCKED) {
                $this->fail($e);
            } else {
                $this->release(5);
            }
            
            
        } catch (Throwable $e) {
            
            $this->processState = ProcessState::BLOCKED;
            $this->rollback(); 
            JobProcessSupport::handleExceptionJobProcess(
                static::class,
                $jobProcessRecord,
                ProcessState::BLOCKED,
                "A fatal error occurred during document processing.",
                "Unhandled fatal error",
                $e,
                'fatal_throwable',
                'Job Critical Error',
                'A fatal exception has caused the job to fail.'
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
        
        $id_file_disk = $this->group_ids['id_file_disk'] ?? null;
        $id_file_document = $this->group_ids['id_file_document'] ?? null;
        
        if (! empty($this->id_document_signature)) {
            
            ModelUtils::createInstanceModel(\App\Models\Documents\Signatures::class)
                ->find($this->id_document_signature)->forceDelete();
            
        }
        
        if ($this->processState === ProcessState::BLOCKED) {
            
            if (! empty($this->id_document)) {
                ModelUtils::createInstanceModel(\App\Models\Documents\Document::class)
                    ->find($this->id_document)->forceDelete();
            }
            
            
            if ($id_file_disk) {
                
                FileDiskSupport::clearFileDisk($id_file_disk);
                
            }
            
            if ($id_file_document) {
                ModelUtils::createInstanceModel(\App\Models\Files\Entity\Documents::class)
                    ->find($id_file_document)->forceDelete();
            }
            
            
        }
        
        
        
        
        
    }
    
    
}
