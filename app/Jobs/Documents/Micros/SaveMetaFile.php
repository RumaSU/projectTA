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

use App\Enums\Documents\Role;
use App\Enums\Documents\Publicity;
use App\Enums\Documents\Signature\ExpiredAction;
use App\Enums\Documents\Signature\Permission;
use App\Enums\Documents\Signature\Status;
use App\Enums\Documents\Signature\Type;


use App\Jobs\Documents\Micros\SaveMetaSignature; 


use App\Exceptions\Jobs\Documents\JobProcessException;
use App\Services\Support\UniqueValueGenerator;
use Symfony\Component\Mime\MimeTypes;
use Throwable;

class SaveMetaFile implements ShouldQueue
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
    protected string $id_file_disk;
    protected string $id_file_disk_entity;
    protected string $id_file_disk_token;
    protected string $id_file_document;
    protected string $fullpath;
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
                    "The process group IDs are incomplete or missing required keys. Please ensure the request payload is valid."
                );
            }
            
            $this->id_document = $group_ids['id_document'];
            
            $document_information = ModelUtils::createInstanceModel(\App\Models\Documents\DocumentInformation::class)
                ->query()
                ->find($group_ids['id_document']);
            
            if (! $document_information) {
                throw new JobProcessException(
                    ProcessStatus::FAILED,
                    ProcessState::BLOCKED,
                    'The related document information could not be found. Document metadata creation aborted.'
                );
            }
            
            $file_disk = ModelUtils::createInstanceModel(\App\Models\Files\Disk::class);
            
            $filename = UniqueValueGenerator::stringByIlluminate(
                64,
                'file_name',
                $file_disk->getTable(),
                $file_disk->getConnectionName()
            );
            
            $sourcePath = ChunkUtils::getConfigStorageChunks() . "/" . $file_meta['filename'];
            
            $extension = MimeTypes::getDefault()->getExtensions($file_meta['mime'])[0];
            $targetPath = "{$filename}.{$extension}";
            
            
            $fileClientName = $document_information->name;
            if ($document_information->name_version > 1) {
                $version = $document_information->name_version - 1 ?? 1;
                $fileClientName .= " [{$version}].{$extension}";
            }
            
            StorageUtils::copy(ChunkUtils::getConfigStorageDisk(), $sourcePath)
                ->to(Entity::DOCUMENT->get_disk(), $targetPath);
            
            $this->fullpath = $targetPath;
            $file = FileDiskSupport::fromDiskPath(Entity::DOCUMENT->get_disk(), $targetPath);
            
            if (! $file->exists()) {
                throw new JobProcessException(
                    ProcessStatus::FAILED,
                    ProcessState::BLOCKED,
                    'The copied file does not exist in the expected disk. Please try again.'
                );
            }
            
            $file_info = StorageUtils::get_metadata(Entity::DOCUMENT->get_disk(), $targetPath);
            
            $uuidFileDisk = $file->create();
            if (! $uuidFileDisk) {
                throw new JobProcessException(
                    ProcessStatus::FAILED,
                    ProcessState::BLOCKED,
                    'Failed to create a record for the uploaded file.'
                );
            }
            $this->id_file_disk = $uuidFileDisk;
            
            $uuidFileDocument = ModelUtils::generateNewUuid(\App\Models\Files\Entity\Documents::class);
            $payloadFileDocument = [
                'id_file_document' => $uuidFileDocument,
                'id_file_disk' => $uuidFileDisk,
                'owner_id' => $owner_id,
                'disk' => Entity::DOCUMENT->get_disk(),
                'path' => $targetPath,
                'file_name' => $targetPath,
                'file_client_name' => $fileClientName,
                'extension' => $extension,
                'mime_type' => $file_info['mime'],
                'size_byte' => $file_info['size'],
            ];
            
            ksort($payloadFileDocument);
            $json = json_encode($payloadFileDocument, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            
            $payloadFileDocument['hash_row'] = Hash::get_default_case()->hash($json);
            $payloadFileDocument['hash_type'] = Hash::get_default();
            
            ModelUtils::createInstanceModel(\App\Models\Files\Entity\Documents::class)
                ->create($payloadFileDocument);
            
            $this->id_file_document = $uuidFileDocument;
            
            $uuidFileDiskEntity = $file->create_entity(
                $owner_id,
                Entity::DOCUMENT,
                $uuidFileDocument,
                $fileClientName
            );
            if (! $uuidFileDiskEntity) {
                throw new JobProcessException(
                    ProcessStatus::FAILED,
                    ProcessState::BLOCKED,
                    'Failed to create the entity record for the uploaded file.'
                );
            }
            
            ModelUtils::create(
                \App\Models\Documents\DocumentFile::class,
                [
                    'id_document_file' => $group_ids['id_document_file'],
                    'id_document_version' => $group_ids['id_document_version'],
                    'id_file_document' => $uuidFileDocument
                ]);
            
            
            $this->id_file_disk_entity = $uuidFileDiskEntity;
            
            $resultToken = $file->create_token($owner_id);
            if ($resultToken === null) {
                throw new JobProcessException(
                    ProcessStatus::FAILED,
                    ProcessState::BLOCKED,
                    'Failed to generate an access token for the file.'
                );
            }
            $this->id_file_disk_token = $resultToken['uuid'];
            
            $payload['group_ids'] = array_merge($group_ids, [
                'id_file_disk' => $this->id_file_disk,
                'id_file_disk_entity' => $this->id_file_disk_entity,
                'id_file_disk_token' => $this->id_file_disk_token,
                'id_file_document' => $this->id_file_document,
            ]);
            ArrayHelper::ksort_recursive($payload);
            
            
            JobProcessSupport::updateJobProcess(
                $jobProcessRecord,
                ProcessStatus::PROCESS,
                ProcessState::PROCESSABLE,
                $payload,
                'Meta file successfully processed and saved.',
                [
                    ['message' => 'The payload is updated with the generated document ID.', 'status' => ProcessStatus::PROCESS],
                    ['message' => 'Step 1/X: Master document record creation successful.', 'status' => ProcessStatus::COMPLETED],
                ],
                null,
                false,
                [],
                'Save Meta File',
                ProcessStatus::COMPLETED->value,
                'Meta file processing and document file linkage have been completed successfully.'
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
            
            
            dispatch(new SaveMetaSignature($this->id_proc_job));
            
            
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
                    "Temporary database issue while saving meta file. Retrying...",
                    "Retryable DB error during meta file processing. Job released for retry.",
                    $e,
                    'query_retryable',
                    'Save Meta File',
                    'Temporary database error during saving file metadata and document file links.'
                );
                
                $this->release(5);
            } else {
                $this->processState = ProcessState::BLOCKED;
                
                $this->rollback(); 
                
                JobProcessSupport::handleExceptionJobProcess(
                    static::class,
                    $jobProcessRecord,
                    ProcessState::BLOCKED,
                    "Database error occurred. Meta file could not be saved.",
                    "Unrecoverable DB error while saving meta file data.",
                    $e,
                    'query_exception',
                    'Save Meta File',
                    'Fatal error while inserting file metadata and linkage. Process stopped.'
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
                "Meta file validation failed. Process halted.",
                "Blocked: Job prerequisites or payload integrity failed during meta file step.",
                $e,
                'job_process_exception',
                'Save Meta File',
                'Job halted due to failed validation or missing document/file prerequisites.'
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
                "Unhandled system error during meta file processing.",
                "Fatal error during meta file execution.",
                $e,
                'fatal_throwable',
                'Save Meta File',
                'Unexpected system-level exception occurred while handling meta file step.'
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
        
        if (!empty($this->id_file_disk)) {
            try {
                FileDiskSupport::clearFileDisk($this->id_file_disk);
            } catch (Throwable $e) {
                JobServiceSupport::log("Failed to rollback id_file_disk", ['id' => $this->id_file_disk, 'error' => $e->getMessage()], 'error', 'rollback');
            }
        }
    
        if (!empty($this->id_file_document)) {
            try {
                $file_document = ModelUtils::createInstanceModel(\App\Models\Files\Entity\Documents::class);
                $find = $file_document->query()->find($this->id_file_document);
                if ($find) {
                    $find->forceDelete();
                }
            } catch (Throwable $e) {
                JobServiceSupport::log("Failed to rollback id_file_document", ['id' => $this->id_file_document, 'error' => $e->getMessage()], 'error', 'rollback');
            }
        }
        
        
        if ($this->processState === ProcessState::BLOCKED && !empty($this->id_document)) {
            try {
                $document = ModelUtils::createInstanceModel(\App\Models\Documents\Document::class);
                $find = $document->query()->find($this->id_document);
                if ($find) {
                    $find->forceDelete();
                }
            } catch (Throwable $e) {
                JobServiceSupport::log("Failed to rollback id_document", ['id' => $this->id_document, 'error' => $e->getMessage()], 'error', 'rollback');
            }
        }
        
    }
    
    
    
    
    
}
