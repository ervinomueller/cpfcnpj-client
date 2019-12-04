<?php

namespace CpfCnpj\Validator;

/**
 * Interface ValidatorInterface
 * @package CpfCnpj\Validator
 */
interface ValidatorInterface
{
    public function validate($value);

    public function getDocumentType(): string;

    public function buildErrorResponse($message, $errorCode = null): string;
}