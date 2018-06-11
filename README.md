
# Judas

O Judas é uma library de logs, é uma implementação que utiliza a lib **[Hermes](https://bitbucket.org/eduzz/hermes)** para enviar os logs para o **rabbitMQ**, e então, utiliza um método store que armazena os logs no **elasticSearch**.

## Instalação

Primeiro, vamos adicionar a dependência e o repositório do **Judas** e do **Hermes** no nosso arquivo composer.json:

```json
{
    "require": {
        "eduzz/hermes": "dev-master",
        "eduzz/judas": "dev-master"
    },
    "repositories": [
        {
            "type": "vcs",
            "url":  "git@bitbucket.org:eduzz/hermes.git"
        },
                {
            "type": "vcs",
            "url":  "git@bitbucket.org:eduzz/judas.git"
        }
    ]
}
```

Após, vamos rodar o comando

```
composer dump-autoload
```

Para atualizar o cache do composer

```
composer install
```

Para instalar as dependência e o judas

PS: É preciso verificar se você está com a chave conectada ao bitbucket no shell onde vai rodar o composer install.

### Instalação em projeto Laravel

O próximo passo é registrar o Judas na lista de service providers, dentro do seu config/app.php, adicione o Judas na sua lista de providers e adicione também a facade do Judas na lista de aliases.

 ```php
'providers'  => [
	// ...
	Eduzz\Judas\JudasLaravelServiceProvider::class,
],
```

```php
'aliases'  => [
	// ...
	'Judas'  =>  Eduzz\Judas\Facades\JudasFacade::class,
],
```

Precisamos limpar nosso cache, atualizar os pacotes e publicar a configuração do Judas:

```bash
php artisan cache:config
composer update
php artisan vendor:publish --tag="config"
```

Se tudo ocorreu bem, a seguinte mensagem sera exibida:

```bash
Copied File [/vendor/eduzz/judas/src/Config/judas.php] To [/config/judas.php]
```

Então, é necessário configurar o judas, no arquivo config/judas.php, nas variáveis elastic_connection, queue_connection e environment, por exemplo:

```php
// Caso seja apenas store
'elastic_connection'  =>  array(
	'host'  =>  env('JUDAS_ELASTIC_HOST'),
	'port'  =>  env('JUDAS_ELASTIC_PORT'),
	'username'  =>  env('JUDAS_ELASTIC_USERNAME'),
	'password'  =>  env('JUDAS_ELASTIC_PASSWORD')
),

// Caso seja para logar
'queue_connection'  =>  array(
	'host'  =>  env('JUDAS_QUEUE_HOST'),
	'port'  =>  env('JUDAS_QUEUE_PORT'),
	'username'  =>  env('JUDAS_QUEUE_USERNAME'),
	'password'  =>  env('JUDAS_QUEUE_PASSWORD')
),

'environment' => 'development'
```

### Instalação em projeto Lumen

Para instalação em projeto lumen, é preciso criar o arquivo de configuração na mão, vamos adicionar um arquivo chamado judas.php na pasta config com o seguinte conteúdo:

```php
// Caso seja apenas store
'elastic_connection'  =>  array(
	'host'  =>  env('JUDAS_ELASTIC_HOST'),
	'port'  =>  env('JUDAS_ELASTIC_PORT'),
	'username'  =>  env('JUDAS_ELASTIC_USERNAME'),
	'password'  =>  env('JUDAS_ELASTIC_PASSWORD')
),

// Caso seja para logar
'queue_connection'  =>  array(
	'host'  =>  env('JUDAS_QUEUE_HOST'),
	'port'  =>  env('JUDAS_QUEUE_PORT'),
	'username'  =>  env('JUDAS_QUEUE_USERNAME'),
	'password'  =>  env('JUDAS_QUEUE_PASSWORD')
),

'environment' => 'development'
```

Vamos também adicionar nosso service provider no register, então na pasta bootstrap/app.php, procure pela linha que faz os registros e adicione:

```php
<?php
// ...
$app->register(Eduzz\Judas\JudasLaravelServiceProvider::class);
// ...
```

Adicione também a chamada para a configuração do judas, por exemplo:

```php
<?php
$app->configure('judas');

return $app;
```

### Instalação em um projeto sem framework Illuminate

Para utilizar o Judas sem Laravel/Lumen, é necessário setar as configurações na mão, exemplo:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';
use Eduzz\Judas\Judas;

$judas = new Judas();

$judas->setQueueConfig([
    'host' => 'localhost',
    'port' => 5672,
    'username' => 'guest',
    'password' => 'guest',
    'vhost' => '/'
]);

// Caso você vá utilizar o store
$judas->setKeeperConfig([
    'host' => 'localhost',
    'port' => 9200,
    'username' => 'elastic',
    'password' => 'elastic',
]);

$judas->environment = 'development';
```

### Sobre o parâmetro environment

O parâmetro environment define para qual index do elastic search a mensagem vai na hora de fazer o store.

O environment padrão é o production, que envia para o index history, quando setamos qualquer outro environment que não seja production, o index para onde o log vai é:

history-"Environment que foi definido"

# Usage

## Logando

Todos os logs do judas viram mensagens do hermes, que são enviadas para o routingKey "judas.store.info".

### Logando mensagens com Laravel/Lumen

Vamos utilizar a injeção de dependência do laravel para instanciar o Judas já pegando as configurações do arquivo config/judas.php, exemplo de um controller do laravel utilizando o judas:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Eduzz\Judas\Judas;

class Controller extends BaseController
{
	private $judas;

	public function  __construct(Judas  $judas) {
		$this->judas  =  $judas;

		parent::__construct();
	}

	public function  method() {
		// Sua lógica aqui

		$this->judas->log(
			// app.module.action
			'myeduzzleg.user.login',
			[
				'agent'  =>  'user',
				'event.data.id'  =>  999,
				'user.id'  =>  999,
				'user.name'  =>  "angelorodriigo.rs@gmail.com",
				'user.ip'  =>  "127.0.0.1",
				'additional_data.status'  =>  'success'
			]
		);
	}
}
```

### Logando mensagens sem framework Illuminate

Para logar algo sem utilizarmos um framework Illuminate, vamos utilizar algum objeto que já setou as configurações e logar da maneira comum.

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';
use Eduzz\Judas\Judas;

$judas = new Judas();

$judas->setQueueConfig([
    'host' => 'localhost',
    'port' => 5672,
    'username' => 'guest',
    'password' => 'guest',
    'vhost' => '/'
]);

$judas->environment = 'development';

$judas->log(
	// app.module.action
	'myeduzzleg.user.login',
	[
		'agent'  =>  'user',
		'event.data.id'  =>  999,
		'user.id'  =>  999,
		'user.name'  =>  "angelorodriigo.rs@gmail.com",
		'user.ip'  =>  "127.0.0.1",
		'additional_data.status'  =>  'success'
	]
);
```

### Tipagem

Os logs do judas são tipados, temos alguns paramêtros obrigatórios e outros não, e temos também parâmetros que são obrigatórios e precisam ser de um valor "x".

Alguns valores não obrigatórios são recomendados para melhor controle.

Os valores do primeiro parâmetro passado, a string, corresponde a:

```
app.module.action
```

Ou seja, esses valores não precisam ser passados no array, como no exemplo demonstrado acima.
___

#### Tabela de parâmetros do array de dados

| Parâmetro | Obrigatório | Valores aceitos |
|--|--|--|
| agent | sim | "procedure", "system", "user" ou "support" |
| app | sim | "cktsun", "myeduzz", "cktleg", "myeduzzleg", "nutror" ou "next"  |
| module | sim | quaisquer |
| action | sim | quaisquer |
| user.id | não | inteiro ou string |
| user.ip | não | string
| user.name | não | string |
| user.email | não | string |
| support.id | não | inteiro ou string |
| support.ip | não | string
| support.name | não | string |
| support.email | não | string |
| system.ip | não | string


## Armazenando logs no elastic search

Os logs do Judas são enviados para uma fila, então, um worker processa os logs e os envia para o elastic search.

### Armazenando logs

No armazenamento do log são necessários alguns parâmetros, como index e date, precisa ser passado um json para o método store.

```php
<?php

$judas->store(
	json_encode(
		[
			"index" => 'history',
			"event" => [
				"date" => "2018-05-14T14:24:28Z"
			]
		]
	)
);

```

Os dados serão armazenados conforme o index passado.
No caso do exemplo acima o recurso será armazenado em "/history/default".

O certo seria, utilizarmos um worker, que, quando um log novo for enviado através do método "Judas->log()" para uma fila, envia esse dado para o ElasticSearch, podemos utilizar o **[Hermes](https://bitbucket.org/eduzz/hermes)** para isso.

```php
<?php

$hermes->addQueue('judas_logs')
	->bind('judas.store.info')
	->getLastQueueCreated();

$hermes->addListenerTo(
	'judas_logs',
	function($msg) use ($judas) {
		$judas->store(json_decode($msg->body));
	}
);

```
