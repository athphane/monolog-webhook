<?php

namespace Athphane\MonologWebhook\Logger;

use Illuminate\Support\Facades\Log;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Spatie\WebhookServer\WebhookCall;

class WebhookLoggingHandler extends AbstractProcessingHandler
{
    public string $url;
    public string $secret;
    public string $prefix;

    public function __construct($level = Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->formatter = new ExceptionLineFormatter();

        $this->url = config('monolog-webhook.url');
        $this->secret = config('monolog-webhook.signing_secret');
        $this->prefix = config('monolog-webhook.header_prefix');
    }

    /**
     * {@inheritDoc}
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(array $record): bool
    {
        if (!$this->isHandling($record)) {
            return false;
        }

        if ($this->processors) {
            $record = $this->processRecord($record);
        }

        $record['formatted'] = $this->getDefaultFormatter()->format($record);

        // Add a stacktrace into the payload.
        // Uses custom ExceptionLineFormatter
        $record['stacktrace'] = $this->getFormatter()->format($record)['context']['exception']['stacktrace'] ?? null;

        $this->write($record);

        return false === $this->bubble;
    }

    /**
     * {@inheritdoc}
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function write(array $record): void
    {
        WebhookCall::create()
            ->url($this->url)
            ->payload(['data' => json_encode($record)])
            ->useSecret($this->secret)
            ->dispatch();
    }
}
