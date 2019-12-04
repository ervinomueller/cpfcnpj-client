<?php

namespace CpfCnpj\Client;

use CpfCnpj\Exceptions\CpfCnpjException;
use CpfCnpj\Factories\ParserFactory;
use CpfCnpj\Parsers\ParserInterface;
use CpfCnpj\Responses\Cnpj;
use CpfCnpj\Responses\Cpf;
use CpfCnpj\Validator\ValidatorInterface;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\StreamInterface;
use Throwable;

/**
 * Class HttpClient
 * @package CpfCnpj\Client
 */
class HttpClient
{
    /**
     * Base url for requests
     * @link https://www.cpfcnpj.com.br/dev/#urlbase
     * @var string
     */
    const BASE_URL = 'https://api.cpfcnpj.com.br';

    /**
     * Token for integration tests
     * @link https://www.cpfcnpj.com.br/dev/#tokens
     * @var string
     */
    private $token = '5ae973d7a997af13f0aaf2bf60e65803';

    /**
     * @var Client
     */
    private $requester;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var string
     */
    private $package;

    /**
     * List of available packages
     * @link https://www.cpfcnpj.com.br/dev/#pacotes
     * @var array
     */
    private $packages = [
        '1',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9',
        '10',
        '11',
    ];

    public function __construct(ValidatorInterface $validator, $token = null, $debug = false)
    {
        $this->validator = $validator;

        if (!is_null($token)) {
            $this->token = $token;
        }

        $this->requester = new Client([
            'base_uri' => self::BASE_URL,
            'debug'    => $debug,
        ]);
    }

    /**
     * @param ValidatorInterface $validator
     */
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @param $package
     * @throws Throwable
     */
    public function setPackage($package)
    {
        $this->package = $this->getPackage($package);
    }

    /**
     * @param $value
     * @return Cnpj|Cpf
     * @throws CpfCnpjException
     * @throws Throwable
     */
    public function get($value)
    {
        $this->validator->validate($value);

        return $this->request('GET', $value);
    }

    /**
     * @param $method
     * @param $data
     * @return Cnpj|Cpf
     * @throws CpfCnpjException
     * @throws Throwable
     */
    private function request($method, $data)
    {
        try {

            $params = [
                $this->token,
                $this->package,
                $data,
            ];

            $response     = $this->requester->request($method, $this->buildUrl($params));
            $responseBody = $response->getBody();
            $statusCode   = $response->getStatusCode();

            return $this->readResponse($statusCode, $responseBody);

        } catch (ClientException $e) {

            $responseBody = $e->getResponse()->getBody();
            $statusCode   = $e->getResponse()->getStatusCode();

            return $this->readResponse($statusCode, $responseBody);

        } catch (Exception $e) {

            throw $e;

        }
    }

    /**
     * @param $statusCode
     * @param $responseBody
     * @return Cnpj|Cpf
     * @throws CpfCnpjException
     * @throws Throwable
     */
    private function readResponse($statusCode, StreamInterface $responseBody)
    {
        $content  = $responseBody;
        $response = json_decode($content);

        throw_if(json_last_error() != JSON_ERROR_NONE, CpfCnpjException::class, $this->buildErrorResponse(json_last_error_msg(), 400));

        $parser = ParserFactory::create((array)$response, $this->validator->getDocumentType());

        throw_if(!($parser instanceof ParserInterface), CpfCnpjException::class, $this->buildErrorResponse('Incorret Parser type'), 400);

        switch ($statusCode) {
            case 200:
                return $parser->parse();
                break;
            case 401:
                throw new CpfCnpjException($this->buildErrorResponse($response->erro, $response->erroCodigo), $statusCode);
            case 404:
                throw new CpfCnpjException($this->buildErrorResponse('Resource not found'), 404);
            default:
                throw new CpfCnpjException($this->buildErrorResponse('Unknown status'), $statusCode);
        }
    }

    /**
     * @param array $params
     * @return string
     */
    private function buildUrl(array $params)
    {
        return implode('/', $params);
    }

    /**
     * @param $package
     * @return mixed
     * @throws Throwable
     */
    private function getPackage($package)
    {
        throw_if(!in_array($package, $this->packages), CpfCnpjException::class, $this->buildErrorResponse('Pacote não encontrado'), 404);

        return $package;
    }

    /**
     * @param $message
     * @param null $errorCode
     * @return string
     */
    private function buildErrorResponse($message, $errorCode = null): string
    {
        return json_encode(['message' => $message, 'errorCode' => $errorCode]);
    }
}