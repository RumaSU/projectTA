<?php

namespace App\Exceptions\Jobs;

use App\Exceptions\BaseException;

class JobException extends BaseException {
    protected string $LOG_CHANNEL = 'jobs_log';
}