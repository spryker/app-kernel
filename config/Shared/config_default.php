<?php

/**
 * This configuration is used for TESTING only and will never be used in production!
 */

use Generated\Shared\Transfer\AppConfigUpdatedTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Shared\AppKernel\AppKernelConstants;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\GlueBackendApiApplication\GlueBackendApiApplicationConstants;
use Spryker\Shared\GlueJsonApiConvention\GlueJsonApiConventionConstants;
use Spryker\Shared\Http\HttpConstants;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\MessageBroker\MessageBrokerConstants;
use Spryker\Shared\ZedRequest\ZedRequestConstants;
use Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig;
use Spryker\Shared\MessageBrokerAws\MessageBrokerAwsConstants;

$config[AppKernelConstants::APP_IDENTIFIER] = Uuid::uuid4()->toString();

// ----------------------------------------------------------------------------
// ------------------------------ Glue Backend API ----------------------------
// ----------------------------------------------------------------------------
$config[GlueBackendApiApplicationConstants::GLUE_BACKEND_API_HOST] = 'api.payment.local';

$config[KernelConstants::ENABLE_CONTAINER_OVERRIDING] = true;
$config[KernelConstants::PROJECT_NAMESPACES] =
$config[GlueBackendApiApplicationConstants::PROJECT_NAMESPACES] = [
    'Spryker',
];
$config[ZedRequestConstants::ZED_API_SSL_ENABLED] = (bool)getenv('SPRYKER_ZED_SSL_ENABLED');

$config[ApplicationConstants::BASE_URL_ZED] = sprintf(
    'https://%s',
    'api.kernel.local',
);

$config[AppKernelConstants::APP_IDENTIFIER] = Uuid::uuid4()->toString();

$config[HttpConstants::URI_SIGNER_SECRET_KEY] = Uuid::uuid4()->toString();

$config[GlueJsonApiConventionConstants::GLUE_DOMAIN] = sprintf(
    '%s://%s',
    getenv('SPRYKER_SSL_ENABLE') ? 'https' : 'http',
    $config[GlueBackendApiApplicationConstants::GLUE_BACKEND_API_HOST] ?: 'localhost',
);

$config[MessageBrokerConstants::MESSAGE_TO_CHANNEL_MAP] =
$config[MessageBrokerAwsConstants::MESSAGE_TO_CHANNEL_MAP] = [
    // App event
    AppConfigUpdatedTransfer::class => 'app-events',
];

$config[MessageBrokerConstants::CHANNEL_TO_TRANSPORT_MAP] = [
    'app-events' => MessageBrokerAwsConfig::HTTP_TRANSPORT,
];

$config[MessageBrokerAwsConstants::CHANNEL_TO_SENDER_TRANSPORT_MAP] = [
    'app-events' => MessageBrokerAwsConfig::HTTP_TRANSPORT,
];
