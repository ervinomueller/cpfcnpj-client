<?php

namespace CpfCnpj\Parsers;

/**
 * Class AbstractParser
 * @package CpfCnpj\Parsers
 */
abstract class AbstractParser implements ParserInterface
{
    /**
     * @var array
     */
    protected $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }
}