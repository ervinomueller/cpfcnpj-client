<?php

namespace CpfCnpj\Parsers;

use CpfCnpj\Responses\Address;
use CpfCnpj\Responses\Branch;
use CpfCnpj\Responses\Cnae;
use CpfCnpj\Responses\Cnpj;
use CpfCnpj\Responses\Fax;
use CpfCnpj\Responses\LegalNature;
use CpfCnpj\Responses\Partner;
use CpfCnpj\Responses\Phone;
use CpfCnpj\Responses\SimplesNacional;
use CpfCnpj\Responses\Situation;
use CpfCnpj\Responses\Size;

/**
 * Class CnpjParser
 * @package CpfCnpj\Parsers
 */
class CnpjParser extends AbstractParser
{
    /**
     * @return Cnpj
     */
    public function parse()
    {
        $response = new Cnpj();

        $response->status           = data_get($this->data, 'status');
        $response->document         = data_get($this->data, 'cnpj');
        $response->company_name     = data_get($this->data, 'razao');
        $response->fantasy_name     = data_get($this->data, 'fantasia');
        $response->activity_start   = data_get($this->data, 'inicioAtividade');
        $response->email            = data_get($this->data, 'email');
        $response->responsible      = data_get($this->data, 'responsavel');
        $response->simples_nacional = $this->parseSimplesNacional();
        $response->address          = $this->parseAddress();
        $response->branches         = $this->parseBranch();
        $response->phones           = $this->parsePhones();
        $response->fax              = $this->parseFax();
        $response->situation        = $this->parseSituation();
        $response->legal_nature     = $this->parseLegalNature();
        $response->cnae             = $this->parseCnae();
        $response->size             = $this->parseSize();
        $response->partners         = $this->parsePartners();
        $response->package          = data_get($this->data, 'pacoteUsado');
        $response->balance          = data_get($this->data, 'saldo');
        $response->query_id         = data_get($this->data, 'consultaID');
        $response->query_time       = data_get($this->data, 'delay');

        return $response;
    }

    /**
     * @return SimplesNacional
     */
    private function parseSimplesNacional()
    {
        $simples = new SimplesNacional();

        $simples->optante = data_get($this->data, 'simplesNacional.optante');
        $simples->start   = data_get($this->data, 'simplesNacional.inicio');
        $simples->finish  = data_get($this->data, 'simplesNacional.fim');

        return $simples;
    }

    /**
     * @return Address
     */
    private function parseAddress()
    {
        $address = new Address();

        $address->zipcode       = data_get($this->data, 'matrizEndereco.cep');
        $address->type          = data_get($this->data, 'matrizEndereco.tipo');
        $address->street        = data_get($this->data, 'matrizEndereco.logradouro');
        $address->street_number = data_get($this->data, 'matrizEndereco.numero');
        $address->complement    = data_get($this->data, 'matrizEndereco.complemento');
        $address->neighborhood  = data_get($this->data, 'matrizEndereco.bairro');
        $address->city          = data_get($this->data, 'matrizEndereco.cidade');
        $address->state         = data_get($this->data, 'matrizEndereco.uf');

        return $address;
    }

    /**
     * @return Branch
     */
    private function parseBranch()
    {
        $branch = new Branch();

        $branch->id   = data_get($this->data, 'matrizfilial.id');
        $branch->type = data_get($this->data, 'matrizfilial.tipo');

        return $branch;
    }

    /**
     * @return array
     */
    private function parsePhones()
    {
        $phones    = [];
        $telefones = data_get($this->data, 'telefones');

        if ($telefones) {
            foreach ($telefones as $telefone) {
                $phone         = new Phone();
                $phone->ddd    = data_get($telefone, 'ddd');
                $phone->number = data_get($telefone, 'numero');

                $phones[] = $phone;
            }
        }

        return $phones;
    }

    /**
     * @return array
     */
    private function parseFax()
    {
        $faxes = [];
        $items = data_get($this->data, 'fax');

        if ($items) {
            foreach ($items as $item) {
                $fax         = new Fax();
                $fax->ddd    = data_get($item, 'ddd');
                $fax->number = data_get($item, 'numero');

                $faxes[] = $fax;
            }
        }

        return $faxes;
    }

    /**
     * @return Situation
     */
    private function parseSituation()
    {
        $situation = new Situation();

        $situation->id        = data_get($this->data, 'situacao.id');
        $situation->name      = data_get($this->data, 'situacao.nome');
        $situation->date      = data_get($this->data, 'situacao.data');
        $situation->reason_id = data_get($this->data, 'situacao.motivoId');
        $situation->reason    = data_get($this->data, 'situacao.motivo');

        return $situation;
    }

    /**
     * @return LegalNature
     */
    private function parseLegalNature()
    {
        $legalNature = new LegalNature();

        $legalNature->code        = data_get($this->data, 'naturezaJuridica.codigo');
        $legalNature->description = data_get($this->data, 'naturezaJuridica.descricao');

        return $legalNature;
    }

    /**
     * @return Cnae
     */
    private function parseCnae()
    {
        $cnae = new Cnae();

        $cnae->division    = data_get($this->data, 'cnae.divisao');
        $cnae->group       = data_get($this->data, 'cnae.grupo');
        $cnae->class       = data_get($this->data, 'cnae.classe');
        $cnae->sub_class   = data_get($this->data, 'cnae.subClasse');
        $cnae->fiscal_code = data_get($this->data, 'cnae.fiscal');
        $cnae->description = data_get($this->data, 'cnae.descricao');

        return $cnae;
    }

    /**
     * @return Size
     */
    private function parseSize()
    {
        $size = new Size();

        $size->id          = data_get($this->data, 'porte.id');
        $size->description = data_get($this->data, 'porte.descricao');

        return $size;
    }

    /**
     * @return array
     */
    private function parsePartners()
    {
        $partners = [];
        $socios   = data_get($this->data, 'socios');

        if ($socios) {
            foreach ($socios as $socio) {
                $partner                = new Partner();
                $partner->name          = data_get($socio, 'nome');
                $partner->type          = data_get($socio, 'tipo');
                $partner->share_capital = data_get($socio, 'capitalSocial');
                $partner->country       = data_get($socio, 'pais');

                $partners[] = $partner;
            }
        }

        return $partners;
    }
}