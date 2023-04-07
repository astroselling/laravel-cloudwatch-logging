<?php

namespace Astroselling\LaravelCloudwatchLogging;

use Monolog\Formatter\LineFormatter;
use Monolog\LogRecord;

class CloudWatchFormatter extends LineFormatter
{
    /**
     * {@inheritDoc}
     */
    public function format(LogRecord $record): string
    {
        $record['context']['log_message'] = $record['message'];
        $record['context']['extra'] = $record['extra'];
        $record['extra'] = [];

        return parent::format($record);
    }
}
