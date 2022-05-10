<?php

namespace FondOfOryx\Zed\LogAwsCloudWatchConnector\Communication;

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use FondOfOryx\Shared\LogAwsCloudWatchConnector\LogAwsCloudWatchConnectorConstants;
use Maxbanton\Cwh\Handler\CloudWatch;
use Monolog\Formatter\JsonFormatter;
use Spryker\Zed\Log\Communication\LogCommunicationFactory as BaseLogCommunicationFactory;

/**
 * @method \FondOfOryx\Zed\LogAwsCloudWatchConnector\LogAwsCloudWatchConnectorConfig getConfig()
 */
class LogAwsCloudWatchConnectorCommunicationFactory extends BaseLogCommunicationFactory
{
    /**
     * @return \Maxbanton\Cwh\Handler\CloudWatch
     */
    public function createCloudWatchHandler(): CloudWatch
    {
        $handler = new CloudWatch(
            $this->createCloudWatchLogsClient(),
            $this->getConfig()->getAwsLogGroupName(),
            $this->getConfig()->getAwsLogStreamName(),
            $this->getConfig()->getAwsLogRetentionDays(),
            $this->getConfig()->getAwsLogBatchSize(),
            $this->getConfig()->getAwsLogTags(),
            $this->getConfig()->getAwsLogLevel(),
        );
        $handler->setFormatter($this->createJsonFormatter());

        return $handler;
    }

    /**
     * @return \Aws\CloudWatchLogs\CloudWatchLogsClient
     */
    protected function createCloudWatchLogsClient(): CloudWatchLogsClient
    {
        return new CloudWatchLogsClient($this->getAwsSdkParams());
    }

    /**
     * @return array
     */
    protected function getAwsSdkParams(): array
    {
        return [
            LogAwsCloudWatchConnectorConstants::AWS_SDK_PARAM_REGION => $this->getConfig()->getAwsRegion(),
            LogAwsCloudWatchConnectorConstants::AWS_SDK_PARAM_VERSION => $this->getConfig()->getAwsVersion(),
            LogAwsCloudWatchConnectorConstants::AWS_SDK_PARAM_CREDENTIALS => [
                LogAwsCloudWatchConnectorConstants::AWS_SDK_PARAM_KEY => $this->getConfig()->getAwsKey(),
                LogAwsCloudWatchConnectorConstants::AWS_SDK_PARAM_SECRET => $this->getConfig()->getAwsSecret(),
            ],
        ];
    }

    /**
     * @return \Monolog\Formatter\JsonFormatter
     */
    protected function createJsonFormatter(): JsonFormatter
    {
        return new JsonFormatter();
    }
}
