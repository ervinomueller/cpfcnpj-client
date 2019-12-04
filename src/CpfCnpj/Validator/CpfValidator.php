<?php

namespace CpfCnpj\Validator;

use CpfCnpj\Exceptions\CpfCnpjException;

/**
 * Class CpfValidator
 * @package CpfCnpj\Validator
 */
class CpfValidator extends AbstractValidator
{
    /**
     * @var string
     */
    protected $document = 'Cpf';

    /**
     * @param $value
     * @throws CpfCnpjException
     */
    public function validate($value)
    {
        $value         = $this->removeNonNumericChars($value);
        $this->isValid = true;

        if (strlen($value) < 11) {
            throw new CpfCnpjException($this->buildErrorResponse($this->getDocumentType() . ' inválido'), 400);
        }

        if (preg_match("/^{$value[0]}{11}$/", $value)) {
            $this->isValid = false;
        }

        for ($s = 10, $n = 0, $i = 0; $s >= 2; $n += $value[$i++] * $s--) ;
        if ($value[9] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            $this->isValid = false;
        }

        for ($s = 11, $n = 0, $i = 0; $s >= 2; $n += $value[$i++] * $s--) ;
        if ($value[10] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            $this->isValid = false;
        }

        if (!$this->isValid) {
            throw new CpfCnpjException($this->buildErrorResponse($this->getDocumentType() . ' inválido'), 400);
        }
    }
}