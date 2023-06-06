<?php

namespace ScandiWebTask;

use Exception;
use Throwable;

class FormException extends Exception
{
    function __construct(
        private readonly string $field,
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getField(): string
    {
        return $this->field;
    }
}