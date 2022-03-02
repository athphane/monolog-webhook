<?php

namespace Athphane\MonologWebhook\Logger;

use Monolog\Logger;

class WebhookCustomLogger
{
    public function __invoke(array $config): Logger
    {
        $logger = new Logger('WebhookCustomLogger');
        $handler = new WebhookLoggingHandler();
        return $logger->pushHandler($handler);
    }
}
