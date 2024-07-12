<?php

namespace Astroselling\LaravelCloudwatchLogging;

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Monolog\Formatter\JsonFormatter;
use Monolog\Level;
use Monolog\Logger;
use PhpNexus\Cwh\Handler\CloudWatch;

class CloudWatchLoggerFactory
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config): Logger
    {
        $sdkParams = $config['sdk'];
        $tags = $config['tags'] ?? [];
        $name = $config['name'] ?? 'cloudwatch';

        // Instantiate AWS SDK CloudWatch Logs Client
        $client = new CloudWatchLogsClient($sdkParams);

        // Log group name, will be created if none
        $groupName = str_replace(' ', '', config('app.name') . '-' . config('app.env'));

        // Log stream name, will be created if none
        $streamName = $config['streamName'];

        // Days to keep logs, 14 by default. Set to `null` to allow indefinite retention.
        $retentionDays = $config['retention'];

        $level = $config['level'] ?? Level::Info;

        // Instantiate handler (tags are optional)
        $handler = new CloudWatch(
            $client,
            $groupName,
            $streamName,
            (int) $retentionDays,
            $config['batch_size'],
            $tags,
            $level,
        );

        $handler->setFormatter(new JsonFormatter());

        // Create a log channel
        $logger = new Logger($name);

        // Set handler
        $logger->pushHandler($handler);

        return $logger;
    }
}
