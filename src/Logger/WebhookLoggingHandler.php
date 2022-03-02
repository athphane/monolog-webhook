<?php

namespace Athphane\MonologWebhook\Logger;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

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
     */
    public function handle(array $record): bool
    {
        if (! $this->isHandling($record)) {
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
        $signature_header = $this->getPrefixedSignature();
        $signature = $this->computeSignature($record);

        $client = new \GuzzleHttp\Client();
        $client->post($this->url, [
            'headers'     => [
                $signature_header => $signature,
            ],
            'form_params' => $record,
        ]);
    }

    /**
     * Calculates a signature for the payload
     *
     * @param  array  $record
     * @return string
     */
    private function computeSignature(array $record): string
    {
        return hash_hmac('sha256', json_encode($record), $this->secret);
    }

    /**
     * Adds the signature header prefix if configured in the environment
     *
     * @return string
     */
    private function getPrefixedSignature(): string
    {
        return "{$this->prefix}Signature";
    }
}
