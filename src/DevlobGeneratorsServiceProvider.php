<?php

namespace Devlob\Generators;

use Illuminate\Support\ServiceProvider;

class DevlobGeneratorsServiceProvider extends ServiceProvider
{
    protected $defer = false;
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/devlob.php' => config_path('devlob.php'),
        ]);

        $this->publishes([
            __DIR__ . '/stubs/' => base_path('resources/stubs/devlob-generators/'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands(
            'Devlob\Generators\Commands\ControllerCommand',
            'Devlob\Generators\Commands\CrudCommand',
            'Devlob\Generators\Commands\ModelCommand',
            'Devlob\Generators\Commands\RequestCommand',
            'Devlob\Generators\Commands\UnitTestCommand',
            'Devlob\Generators\Commands\ViewCommand'
        );
    }
}
