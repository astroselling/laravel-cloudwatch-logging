<?php

namespace Astroselling\LaravelCloudwatchLogging;

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Maxbanton\Cwh\Handler\CloudWatch;
use Monolog\Formatter\LineFormatter;
use Monolog\Logger;

class CloudWatchFormatter extends LineFormatter
{
    /**
     * {@inheritDoc}
     */
    public function format(array $record): string
    {
        $record['context']['log_message'] = $record['message'];
        $record['context']['extra'] = $record['extra'];
        $record['message'] = null;
        $record['extra'] = [];
        return parent::format($record);
    }
}
