<?php

use CpfCnpj\Client\HttpClient;
use CpfCnpj\Exceptions\CpfCnpjException;
use CpfCnpj\Responses\Cnpj;
use CpfCnpj\Responses\Cpf;
use CpfCnpj\Validator\CnpjValidator;
use CpfCnpj\Validator\CpfValidator;
use PHPUnit\Framework\TestCase;

class HttpClientTest extends TestCase
{
    /**
     * @var HttpClient
     */
    private $http;

    /**
     * @var string
     */
    private $cpf = '43060021015';

    /**
     * @var string
     */
    private $cpf_package = '9';

    /**
     * @var string
     */
    private $cnpj = '28152565000103';

    /**
     * @var string
     */
    private $cnpj_package = '6';

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        $validator  = new CpfValidator();
        $this->http = new HttpClient($validator);
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    public function tearDown()
    {
        $this->http = null;
    }

    /**
     * @throws Throwable
     * @throws CpfCnpjException
     */
    public function testCpf()
    {
        $validator = new CpfValidator();
        $this->http->setValidator($validator);

        $this->http->setPackage($this->cpf_package);

        $response = $this->http->get($this->cpf);
        $this->assertInstanceOf(Cpf::class, $response);
    }

    /**
     * @throws CpfCnpjException
     * @throws Throwable
     */
    public function testCnpj()
    {
        $validator = new CnpjValidator();
        $this->http->setValidator($validator);

        $this->http->setPackage($this->cnpj_package);

        $response = $this->http->get($this->cnpj);
        $this->assertInstanceOf(Cnpj::class, $response);
    }
}