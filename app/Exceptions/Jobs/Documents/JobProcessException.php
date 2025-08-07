<?php

namespace App\Exceptions\Jobs\Documents;

use App\Enums\Jobs\Documents\ProcessStatus;
use App\Enums\Jobs\Documents\ProcessState;

use App\Exceptions\Jobs\JobException;

class JobProcessException extends JobException
{
    protected string $LOG_CHANNEL = 'jobs_documents';
    
    public function __construct(
        public readonly ProcessStatus $status,
        public readonly ProcessState $state,
        string $message,

    ) {
        parent::__construct($message);
    }
    
}
