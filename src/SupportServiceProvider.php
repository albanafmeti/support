<?php

namespace Noisim\Support;

use Illuminate\Support\ServiceProvider;
use Softmogul\Support\Commands\ValidatorMakeCommand;

class SupportServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ValidatorMakeCommand::class,
            ]);
        }
        
        $this->mergeConfigFrom(__DIR__ . '/config/support.php', 'support');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // ...
    }
}
