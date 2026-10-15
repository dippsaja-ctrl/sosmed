<?php

namespace App\Exceptions;

use RuntimeException;

class PublishException extends RuntimeException
{
    /** $retryable=false: kesalahan permanen (mis. akun terputus), jangan diulang otomatis. */
    public function __construct(string $message, public bool $retryable = true)
    {
        parent::__construct($message);
    }
}
