<?php

namespace App\Exceptions;

use App\Exceptions\BaseException;

class InvalidArgumentException extends BaseException {
    public function __construct(
        string $message = "One or more arguments provided are invalid.", // Pesan default yang lebih baik
        int $code = 0,
        \Throwable|null $previous = null,
        bool $autoLog = true
    ) {
        parent::__construct($message, $code, $previous, $autoLog);
    }
    
    
    
}