<?php

namespace CpfCnpj\Parsers;

use CpfCnpj\Responses\Cpf;

/**
 * Class CpfParser
 * @package CpfCnpj\Parsers
 */
class CpfParser extends AbstractParser
{
    /**
     * @return Cpf
     */
    public function parse()
    {
        $response = new Cpf();

        $response->status     = data_get($this->data, 'status');
        $response->document   = data_get($this->data, 'cpf');
        $response->name       = data_get($this->data, 'nome');
        $response->birthdate  = data_get($this->data, 'nascimento');
        $response->mother     = data_get($this->data, 'mae');
        $response->gender     = data_get($this->data, 'genero');
        $response->situation  = data_get($this->data, 'situacao');
        $response->package    = data_get($this->data, 'pacoteUsado');
        $response->balance    = data_get($this->data, 'saldo');
        $response->query_id   = data_get($this->data, 'consultaID');
        $response->query_time = data_get($this->data, 'delay');

        return $response;
    }
}