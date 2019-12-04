<?php

namespace CpfCnpj\Validator;

/**
 * Class AbstractValidator
 * @package CpfCnpj\Validator
 */
abstract class AbstractValidator implements ValidatorInterface
{
    /**
     * @var bool
     */
    protected $isValid;

    /**
     * @var string
     */
    protected $document;

    /**
     * @param $value
     * @return string
     */
    protected function removeNonNumericChars($value): string
    {
        return preg_replace('/[^\d]/', '', $value);
    }

    /**
     * @return string
     */
    public function getDocumentType(): string
    {
        return $this->document;
    }

    /**
     * @param string $message
     * @param null $errorCode
     * @return string
     */
    public function buildErrorResponse($message = "", $errorCode = null): string
    {
        return json_encode(['message' => $message, 'errorCode' => $errorCode]);
    }
}