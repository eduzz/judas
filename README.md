# Judas

O Judas é uma lib de logs desenvolvida utilizando como dependência a biblioteca Eduzz\Hermes.

** Dependências: PHP 5.3 ** Devido ao uso de namespaces.

** Dependências: bcmath e mbstring ** Devido ao uso do [eduzz\hermes](https://bitbucket.org/eduzz/hermes) para envio de logs para o rabbitmq.

## Instalação

Primeiro, vamos adicionar a dependência e o repositório do judas e do hermes no nosso arquivo composer.json:

```json
{
    "require": {
        "eduzz/judas": "dev-master"
    },
    "repositories": [
        {
            "type": "vcs",
            "url":  "git@bitbucket.org:eduzz/judas.git"
        },
        {
            "type": "vcs",
            "url":  "git@bitbucket.org:eduzz/hermes.git"
        }
    ]
}
```

## Para laravel

O próximo passo é registrar o Judas na lista de service providers, dentro do seu config/app.php, adicione o Judas na sua lista de providers e a Facade do Judas

```php
'providers' => [
    // ...

    Eduzz\Judas\JudasLaravelServiceProvider::class,
],
```

Nossa facade:

```php
'aliases' => [
    // ...

    'Judas' => Eduzz\Judas\Facades\JudasFacade::class,
],
```

Precisamos limpar nosso cache, atualizar os pacotes e publicar a configuração do hermes:

```bash
php artisan cache:config
composer update
php artisan vendor:publish --tag="config"
```

Se tudo ocorreu bem, a seguinte mensagem sera exibida:

```bash
Copied File [/vendor/eduzz/judas/src/Config/judas.php] To [/config/judas.php]
```

Após, o judas já pode começar a ser utilizado.

O arquivo de configurações do judas possui 4 paramêtros:

```php
<?php

return array(
    queue_connection => null, // Dados de acesso a fila do rabbitMQ

    elastic_connection => null, // Dados de acesso ao elasticSearch

    default_queue_connection => [], // Dados padrões de conexão com o rabbitMQ

    default_elastic_connection => [] // Dados padrões de conexão com o elasticSearch
);
```


## Enviando o log com o Judas

Para enviar o log com o Judas, utilizando nosso Service Provider, é necessário utilizar nossa classe e passar um objeto pelo paramêtro, por exemplo:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Eduzz\Judas\Judas;

class JudasController extends Controller
{
    public function test(Request $request, Judas $judas) {
        $judas->log('cktsun.invoice.created',
        [
            'agent' => 'user',
            'event.data.id' => 123,
            'user.id' => 123,
            'user.name' => 'johndoe',
            'user.ip' => '45.4.72.82',
            'additional_data.amount' => 900.00
        ]);
    }
}
```

O log do judas por enquanto utiliza um schema de array, caso o schema não seja passado, uma exceção será disparada, nosso schema é:

```php
'*agent' => ['choose', 'procedure', 'system', 'user', 'support'],
        '*event.app' => ['choose', 'cktsun', 'myeduzz', 'cktleg', 'myeduzzleg', 'nutror', 'next'],
        '*event.module' => ['type', 'expected' => 'string'],
        '*event.action' => ['type', 'expected' => 'string'],
        '*event.data.id' => ['type', 'expected' => 'integer'],
        '*event.date' => [
            'regex',
            'pattern' => '/^\b[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}Z\b/',
            'example' => '2018-04-06T14:10:57Z'
        ],
        'user.id' => ['type', 'expected' => 'integer'],
        'user.name' => ['type', 'expected' => 'string'],
        'user.ip' => [
            'regex',
            'pattern' => '/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/',
            'example' => '123.123.123.123'
        ],
        'additional_data' => '*' // Any other data goes here
```

Os campos começando com * são obrigatórios.

Os campos que possuem um array como valor, podem ser de 3 tipos:

- choose
- regex
- type

Quando o tipo for choose, um dos valores a partir da posição 1 do array devem ser passados.

Quando o tipo for regex, um valor que seja compatível com o regex da posição 1 do array deve ser passado.

Quando o tipo for type, o valor passado deve ter o seu tipo igual o do valor do index 'expected' do array.

## Uso sem laravel

Caso você não esteja utilizando laravel e gostaria de utilizar o judas, é possível passar como paramêtro as configurações através do método $judas->setQueueConfig.

```php
<?php

$judas = new Judas();

$judas->setQueueConfig([
    'host' => 'customHost',
    'port' => 1234,
    'username' => 'customUser',
    'password' => 'customPassword'
]);

$judas->log('cktsun.invoice.created',
[
    'agent' => 'user',
    'event.data.id' => 123,
    'user.id' => 123,
    'user.name' => 'johndoe',
    'user.ip' => '45.4.72.82',
    'additional_data.amount' => 900.00
]);
```

## Armazenando log's com o Judas

Para armazenar logs com o Judas, configuramos as  credenciais de acesso ao ElasticSearch e então passamos o seguinte método:

```php
<?php

$judas->store([
    'id' => 1,
    'name' => 'John doe'
]);
```

Os logs serão armazenados na localização http://host/history/default.

## Arquitetura padrão

Por ser uma requisição HTTP, o Judas funciona melhor, utilizando algum sistema para gerenciamento de filas, por isso optamos por utilizar o rabbitMQ para armazenamento temporário dos logs.

Exemplo de worker de store com o eduzz\hermes\consumer.

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Eduzz\Judas\Judas;
use Eduzz\Hermes\Hermes;

$judas = new Judas();
$hermes = new Hermes();

$hermes->addListenerTo('cktsun.invoice.created', function($msg) use ($judas) {
    $judas->store(json_decode($msg->body));
});

```
