<?php

namespace Athphane\MonologWebhook;

use Illuminate\Support\ServiceProvider;

class MonologWebhookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/monolog-webhook.php'         => config_path('monolog-webhook.php'),
            __DIR__ . '/../config/monolog-webhook-logging.php' => config_path('monolog-webhook-logging.php'),
        ], 'monolog-webhook');
    }

    public function register()
    {
        // We merge the config file directly to the logging.channels config array.
        $this->mergeConfigFrom(
            __DIR__ . '/../config/monolog-webhook-logging.php', 'logging.channels'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../config/monolog-webhook.php', 'monolog-webhook'
        );
    }
}
