<?php

namespace App\Services\Support\Documents;


use App\Services\Support\JobServiceSupport;
use App\Services\Support\AbstractLogSupport;

use App\Enums\Jobs\Documents\ProcessState;
use App\Enums\Jobs\Documents\ProcessStatus;
use App\Enums\Jobs\Documents\ProcessType;

use App\Exceptions\Jobs\Documents\JobProcessException;

use App\Models\Jobs\Documents\Process as AppJobsProcessDoc;


use App\Libraries\ArrayHelper;

use App\Utils\LogUtils;

use App\Trait\HasMeta;

use Carbon\Carbon;
use Throwable;

class JobProcessSupport extends AbstractLogSupport {
    use HasMeta;
    protected static string $LOG_CHANNEL_NAME = 'jobs_documents';
    
    private const TRACE_TAKE = 3;
    public const PROCESS_TABLE = 'app_jobs_process_docs';
    public const ORIGINAL_PAYLOAD_KEYS  = [
        'file_meta', 
        'request', 
        'owner_id', 
        'session_id',
        'group_ids'
    ];
    
    public const GENERATED_ID_KEYS  = [
        'id_document', 
        'id_document_collaborator', 
        'id_document_file', 
        'id_document_versions', 
        'id_document_signatures', 
        'id_document_signatures_permission', 
        'id_document_signatures_signer'
    ];
    
    
    public static function validateJobProcess(
        AppJobsProcessDoc $jobProcessRecord,
        
    ): array {
        if (! $jobProcessRecord) {
            throw new JobProcessException(
                ProcessStatus::FAILED, 
                ProcessState::BLOCKED, 
                'The job process could not be found. Please try again later.'
            );
        }
        
        if ($jobProcessRecord->is_cancelled) {
            throw new JobProcessException(
                ProcessStatus::CANCELLED, 
                ProcessState::BLOCKED, 
                'The process has been cancelled and cannot proceed.'
            );
        }
        
        if ($jobProcessRecord->process_state === ProcessState::BLOCKED->value) {
            throw new JobProcessException(
                ProcessStatus::FAILED, 
                ProcessState::BLOCKED, 
                'This process is currently blocked and cannot continue.'
            );
        }
        
        $jobProcessArray = $jobProcessRecord->toArray();
        $payload = $jobProcessArray['payload'];
        // if (! ArrayHelper::key_exists(['integrity_hash', 'integrity_id'], $payload) ) {
        //     throw new JobProcessException(
        //         ProcessStatus::FAILED, 
        //         ProcessState::BLOCKED, 
        //         'The provided process data is incomplete or invalid.'
        //     );
        // }
        
        if (! static::checkMeta($payload, static::ORIGINAL_PAYLOAD_KEYS ) ) {
            throw new JobProcessException(
                ProcessStatus::FAILED, 
                ProcessState::BLOCKED, 
                'Essential metadata is missing from the process data.'
            );
        }
        
        // $originalIntegrityHash = $payload['integrity_hash'];
        // $originalIntegrityId = $payload['integrity_id'];
        // $groupIdsFromPayload = $payload['group_ids'];
        
        // $originalPayloadData = collect($payload)
        //     ->filter( function($v, $k) {
        //         return in_array($k, static::ORIGINAL_PAYLOAD_KEYS);
        //     })->toArray();
        // ArrayHelper::ksort_recursive($originalPayloadData);
        // LogUtils::log('jobs_documents', 'data', $originalPayloadData);
        
        // if (hash('sha256', json_encode($originalPayloadData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK)) !== 
        //     $originalIntegrityHash) {
        //     throw new JobProcessException(
        //         ProcessStatus::FAILED, 
        //         ProcessState::BLOCKED, 
        //         'The process data has failed validation. Please ensure the request is valid.'
        //     );
        // }
        // if (hash('sha256', json_encode($groupIdsFromPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK)) !== 
        //     $originalIntegrityId) {
        //     throw new JobProcessException(
        //         ProcessStatus::FAILED, 
        //         ProcessState::BLOCKED, 
        //         'The document structure does not match the expected integrity check.'
        //     );
        // }
        
        if (! ServicesSupport::checkAccept($payload['file_meta']['mime'])) {
            throw new JobProcessException(
                ProcessStatus::FAILED, 
                ProcessState::BLOCKED, 
                'The uploaded file type is not allowed.'
            );
        }
        if (! ServicesSupport::checkLimit($payload['file_meta']['size'])) {
            throw new JobProcessException(
                ProcessStatus::FAILED, 
                ProcessState::BLOCKED, 
                'The uploaded file size exceeds the allowed limit.'
            );
        }
         
        return $jobProcessArray;
    }
    
    
    public static function updateJobProcess(
        AppJobsProcessDoc $jobProcessRecord,
        ProcessStatus $status,
        ProcessState|null $process_state = null,
        array|null $payload = null,
        string $message,
        array|string|null $additionalMessageStates  = null,
        string|null $retryReason = null,
        bool $isCancelled = false,
        array|null $exceptionDetails = null,
        string $stepName,
        string $stepStatus,
        string $stepMessage,
    ): void {
        $jobProcessRecord->refresh();
        
        if ($isCancelled || $jobProcessRecord->is_cancelled) {
            throw new JobProcessException(ProcessStatus::CANCELLED, ProcessState::BLOCKED, "Process was cancelled.");
        }
        
        $finalStatus = $status->value;
        $finalState = ($processState ?? ProcessState::PROCESSABLE)->value;
        
        $updateData = [
            'status' => $status,
            'process_state' => $process_state,
            'payload' => $payload ?? $jobProcessRecord->payload,
            'message' => $message,
            'retryable' => false,
            'is_cancelled' => $isCancelled,
            'attempts' => ($jobProcessRecord->attempts ?? 0) + 1,
            'exception' => $exceptionDetails,
        ];
        
        if ($retryReason && $finalStatus !== ProcessStatus::CANCELLED->value && $finalState !== ProcessState::BLOCKED->value) {
            $updateData['retry_reason'] = $retryReason;
            $updateData['retryable'] = true;
        }
        
        // Update process_detail
        $detail = static::createNewProcessDetail(
            $jobProcessRecord,
            $stepName,
            $stepStatus,
            $stepMessage,
            $exceptionDetails
        );
        
        $updateData['process_detail'] = array_merge(
            $jobProcessRecord->process_detail ?? [],
            [$detail]
        );
        
        $messageStates = $jobProcessRecord->message_state ?? [];
        if (!empty($additionalMessageStates)) {
            $mergedMessages = is_array($additionalMessageStates)
                ? $additionalMessageStates
                : [[ 'message' => $additionalMessageStates, 'status' => $finalStatus ]];
            
            $updateData['message_state'] = array_merge($messageStates, $mergedMessages);
        }
        
        $jobProcessRecord->update($updateData);
    }
    
    
    public static function handleExceptionJobProcess(
        string $jobClassName,
        AppJobsProcessDoc|null $jobProcessRecord,
        ProcessState $processState,
        string $message,
        string $additionalMessageStates,
        Throwable $exception,
        string $errorType,
        string $processStepName,
        string $processStepMessage
    ): void {
        
        $exceptionDetails = [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => collect($exception->getTrace())->take(static::TRACE_TAKE),
        ];
        
        JobServiceSupport::log(
            "[{$jobClassName}] {$errorType}: {$exception->getMessage()}",
            [
                'job_class' => $jobClassName,
                'error_type' => $errorType,
                'job_id' => $jobProcessRecord?->getKey(),
                'table' => $jobProcessRecord?->getTable(),
                'exception' => $exceptionDetails
            ],
            'error'
        );
        static::logException($exception);
        
        if ($jobProcessRecord) {
            
            $finalStatus = ($processState === ProcessState::BLOCKED)
                ? ProcessStatus::FAILED
                : ProcessStatus::RETRIED;
            
            $retry_reason = ($finalStatus === ProcessStatus::RETRIED)
                ? "System retry due to " . $exception->getMessage()
                : null;
            
            $detailedMessageState = [
                [
                    'message' => $additionalMessageStates,
                    'status' => $finalStatus->value
                ],
                [
                    'message' => "Exception caught: " . get_class($exception),
                    'status' => $finalStatus->value
                ],
                [
                    'message' => "System has marked this process as '" . $finalStatus->name . "' and will " . 
                        ($finalStatus === ProcessStatus::RETRIED ? "retry automatically." : "stop execution."),
                    'status' => $finalStatus->value
                ]
            ];
            
            $commonMessage = match ($finalStatus) {
                ProcessStatus::RETRIED => "Sistem mengalami kendala teknis dan akan mencoba ulang proses.",
                ProcessStatus::FAILED => "Proses dihentikan karena kendala teknis.",
                default => $message
            };
            
            
            static::updateJobProcess(
                $jobProcessRecord,
                $finalStatus,
                $processState,
                null,
                $commonMessage,
                $detailedMessageState,
                $retry_reason,
                false,
                $exceptionDetails,
                $processStepName,
                $finalStatus->value,
                $processStepMessage
            );
            
        }
    }
    
    
    public static function createNewProcessDetail(
        AppJobsProcessDoc $jobProcessRecord,
        string $step,
        string $status = 'complete',
        string $message,
        mixed $exception = null,
    ): array {
        $currentAttempt = $jobProcessRecord->attempts ?? 0;
        
        return  [
            "attempt" => $currentAttempt + 1, 
            "step" => $step,
            "actor" => "system", 
            "status" => $status, 
            "message" => $message, 
            "timestamp" => Carbon::now()->toIso8601String(), 
            "exception" => $exception,
        ];
        
    }
    
     
    public static function log(string $message, array $context = [], string $level = 'info', string|null $tag = null, bool $add_identifier = false): void {
        LogUtils::log(
            static::$LOG_CHANNEL_NAME,
            $message,
            $context,
            $level,
            $tag,
            $add_identifier
        );
    }
    
    public static function logException(Throwable $e, string $level = 'error', string|null $tag = null, bool $add_identifier = false): void {
        LogUtils::logException(
            $e,
            static::$LOG_CHANNEL_NAME,
            $level,
            $tag,
            $add_identifier
        );
    }
    
}