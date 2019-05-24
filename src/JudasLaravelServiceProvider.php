<?php

namespace Eduzz\Judas;

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
        $this->app->singleton(
            'Eduzz\Judas\Judas', function ($app) {

                $token = config('judas.token');
                $baseUrl = config('judas.baseUrl', '');
                $env = config('judas.environment', 'development');

                $judas = new Judas($baseUrl, $token);

                if (!empty($env)) {
                    $judas->setEnvironment($env);
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
        return ['Eduzz\Judas\Judas'];
    }
}
