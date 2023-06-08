<?php

namespace ScandiWebTask;

use Exception;
use Throwable;

class FormException extends Exception
{
    function __construct(
        private readonly array $errors,
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}