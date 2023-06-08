<?php

namespace ScandiWebTask\Validator;

use ScandiWebTask\FormException;

abstract class BaseValidator
{
    protected array $errors = [];

    public abstract function validate(\PDO $pdo, array $input): bool;

    public function handleErrors(): void
    {
        throw new FormException($this->errors);
    }
}