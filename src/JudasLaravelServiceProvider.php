<?php

namespace Eduzz\Judas;

use Eduzz\Judas\Judas;
use Illuminate\Support\ServiceProvider;

class JudasLaravelServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/Config/judas.php' => config_path('judas.php'),
        ], 'config');
    }

    public function register()
    {
        $this->app->bind('Eduzz\Judas\Judas', function ($app) {
            $judas = new Judas();

            if (!empty(config('judas.queue_connection'))) {
                $judas->setQueueConfig(config('judas.queue_connection'));
            } else {
                $judas->setQueueConfig(config('judas.default_queue_connection'));
            }

            if (!empty(config('judas.elastic_connection'))) {
                $judas->setElasticConfig(config('judas.elastic_connection'));
            } else {
                $judas->setElasticConfig(config('judas.default_elastic_connection'));
            }

            return $judas;
        });
    }

    public function provides()
    {
        return [Judas::class];
    }
}
