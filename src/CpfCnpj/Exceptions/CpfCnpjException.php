<?php

namespace CpfCnpj\Exceptions;

use Exception;
use Throwable;

/**
 * Class CpfCnpjException
 * @package CpfCnpj\Exceptions
 */
class CpfCnpjException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}