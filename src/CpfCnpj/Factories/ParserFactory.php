<?php

namespace CpfCnpj\Factories;

use CpfCnpj\Parsers\CnpjParser;
use CpfCnpj\Parsers\CpfParser;

/**
 * Class ParserFactory
 * @package CpfCnpj\Factories
 */
class ParserFactory
{
    private static $document;

    /**
     * @param array $content
     * @param $document
     * @return CnpjParser|CpfParser|null
     */
    public static function create(array $content, $document)
    {
        self::$document = strtolower($document);

        switch (self::$document) {
            case 'cpf':
                $parser = new CpfParser($content);
                break;
            case 'cnpj':
                $parser = new CnpjParser($content);
                break;
            default:
                return null;
        }

        return $parser;
    }
}