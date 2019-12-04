<?php

namespace CpfCnpj\Validator;

use CpfCnpj\Exceptions\CpfCnpjException;

/**
 * Class CnpjValidator
 * @package CpfCnpj\Validator
 */
class CnpjValidator extends AbstractValidator
{
    /**
     * @var string
     */
    protected $document = 'Cnpj';

    /**
     * @param $value
     * @throws CpfCnpjException
     */
    public function validate($value)
    {
        $value         = $this->removeNonNumericChars($value);
        $base          = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $this->isValid = true;

        if (strlen($value) < 14) {
            throw new CpfCnpjException($this->buildErrorResponse($this->getDocumentType() . ' inválido'), 400);
        }

        if (preg_match("/^{$value[0]}{14}$/", $value) > 0) {
            $this->isValid = false;
        }

        for ($i = 0, $n = 0; $i < 12; $n += $value[$i] * $base[++$i]) ;
        if ($value[12] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            $this->isValid = false;
        }

        for ($i = 0, $n = 0; $i <= 12; $n += $value[$i] * $base[$i++]) ;
        if ($value[13] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            $this->isValid = false;
        }

        if (!$this->isValid) {
            throw new CpfCnpjException($this->buildErrorResponse($this->getDocumentType() . ' inválido'), 400);
        }
    }
}