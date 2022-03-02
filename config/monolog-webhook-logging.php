<?php

use Athphane\MonologWebhook\Logger\WebhookCustomLogger;
use Athphane\MonologWebhook\Logger\WebhookLoggingHandler;

return [
    'webhook-stack' => [
        'driver'            => 'stack',
        'channels'          => ['single', 'webhook'],
        'ignore_exceptions' => false,
    ],

    'webhook' => [
        'driver'  => 'custom',
        'handler' => WebhookLoggingHandler::class,
        'via'     => WebhookCustomLogger::class,
        'level'   => 'debug',
    ],
];
