<?php

/**
 * This configuration is used for TESTING only and will never be used in production!
 */

use Generated\Shared\Transfer\AppConfigUpdatedTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Shared\AppKernel\AppKernelConstants;
use Spryker\Shared\MessageBroker\MessageBrokerConstants;
use Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig;

$config[MessageBrokerConstants::MESSAGE_TO_CHANNEL_MAP] = [
    AppConfigUpdatedTransfer::class => 'app-events',
];

$config[MessageBrokerConstants::CHANNEL_TO_SENDER_TRANSPORT_MAP] = [
    'app-events' => MessageBrokerAwsConfig::HTTP_CHANNEL_TRANSPORT,
];

$config[AppKernelConstants::APP_IDENTIFIER] = Uuid::uuid4()->toString();
