<?php

namespace Athphane\MonologWebhook\Logger;

use Monolog\Formatter\NormalizerFormatter;
use Throwable;

class ExceptionLineFormatter extends NormalizerFormatter
{
    /**
     * @param  Throwable  $e
     * @param  int        $depth
     * @return array
     */
    protected function normalizeException(Throwable $e, int $depth = 0): array
    {
        return ['stacktrace' => $e->getTrace()];
    }
}
