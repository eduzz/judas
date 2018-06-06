<?php

namespace Eduzz\Judas;

use Eduzz\Judas\Judas;
use Illuminate\Support\ServiceProvider;

class JudasLaravelServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function boot()
    {
        $this->publishes(
            [
            __DIR__ . '/Config/judas.php' => $this->getConfigPath('judas.php'),
            ], 'config'
        );
    }

    public function register()
    {
        $this->app->bind(
            'Eduzz\Judas\Judas', function ($app) {
                $judas = new Judas();

                $judas->setQueueConfig(config('judas.queue_connection'));
                $judas->setKeeperConfig(config('judas.elastic_connection'));

                $judas->environment = 'development';

                if(!empty(config('judas.environment'))) {
                    $judas->environment = config('judas.environment');
                }

                return $judas;
            }
        );
    }

    /**
     * Get the configuration file path.
     *
     * @param string $path
     * @return string
     */
    private function getConfigPath($path = '') {
        return $this->app->basePath() . '/config' . ($path ? '/' . $path : $path);
    }

    public function provides()
    {
        return [Judas::class];
    }
}
