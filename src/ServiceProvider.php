<?php

namespace Kingdee\Jdy;

use Kingdee\Jdy\Factory;
use Kingdee\Jdy\MiniProgram\Application as MiniProgram;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;
class ServiceProvider extends LaravelServiceProvider
{
    protected $defer = true;

    /**
     * Boot the provider.
     */
    public function boot()
    {
    }

    /**
     * Setup the config.
     */
    protected function setupConfig()
    {
        $source = realpath( __DIR__ . '/config/Jdy.php' );

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('Jdy.php')], 'Jdy');
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('Jdy');
        }

        $this->mergeConfigFrom($source, 'Jdy');
    }

    /**
     * Register the provider.
     */
    public function register()
    {
        $this->setupConfig();

        $apps = [
            'mini_program' => MiniProgram::class,
        ];

        foreach ($apps as $name => $class) {
            if (empty(config('Jdy.'.$name))) {
                continue;
            }

            if (!empty(config('Jdy.'.$name.'.app_id')) ) {
                $accounts = [
                    'default' => config('Jdy.'.$name),
                ];
                config(['Jdy.'.$name.'.default' => $accounts['default']]);
            } else {
                $accounts = config('Jdy.'.$name);
            }

            foreach ($accounts as $account => $config) {
                $this->app->singleton("Jdy.{$name}.{$account}", function ($laravelApp) use ($name, $account, $config, $class) {
                    $app = new $class(array_merge(config('Jdy.defaults', []), $config));
                    if (config('Jdy.defaults.use_laravel_cache')) {
                        $app['cache'] = $laravelApp['cache.store'];
                    }
                    $app['request'] = $laravelApp['request'];

                    return $app;
                });
            }
            $this->app->alias("Jdy.{$name}.default", 'Jdy.'.$name);

            $this->app->alias('Jdy.'.$name, $class);
        }
    }
}