# cpfcnpj-client
Wrapper for https://cpfcnpj.com.br service
  
  
  
## Requisitos

- PHP `7.2+`
- PHP `ext-json`
- Pacote `guzzlehttp/guzzle`
- Pacote `laravel/helpers`
  
  
  
## Instalação

Instale utilizando o Composer: 

    composer require ervinomueller/cfpcnpj-client
  
  
## Utilização (CPF)

```php
<?php

namespace App\Services;

use CpfCnpj\Client\HttpClient;
use CpfCnpj\Validator\CpfValidator;

class CpfValidation
{
    private $httpClient;

    public function __construct()
    {
        $validator = new CpfValidator();
        $token = '5ae973d7a997af13f0aaf2bf60e65803';
        
        $this->httpClient = new HttpClient($validator, $token);
    }

    public function consult($cpf, $package)
    {
        $this->httpClient->setPackage($package);
        $result = $this->httpClient->get($cpf);

        var_dump($result);exit;
    }
}

$cpfValidation = new CpfValidation();
$cpfValidation->consult('06413137053', 1);

```

O resultado será semelhante a isso:

```php
object(CpfCnpj\Responses\Cpf)[777]
  public 'status' => int 1
  public 'document' => string '064.131.370-53' (length=14)
  public 'name' => string 'Jose Maria' (length=10)
  public 'birthdate' => null
  public 'mother' => null
  public 'gender' => null
  public 'situation' => null
  public 'package' => int 1
  public 'balance' => int 123
  public 'query_id' => string '11bb22cc33dd44ee' (length=16)
  public 'query_time' => float 0.3
  
 ```

O resultado varia de acordo com o pacote informado no segundo parâmetro do método `consult`.
A lista de pacotes pode ser encontrada [aqui](https://www.cpfcnpj.com.br/pacotes.html).

Caso o token não seja informado, será utilizado o ambiente de testes.
  
  
  
## Licença

Este pacote é um software open-source sob a [MIT license](LICENSE).
