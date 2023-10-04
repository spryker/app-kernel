# AppKernel Module
[![Latest Stable Version](https://poser.pugx.org/spryker/app-kernel/v/stable.svg)](https://packagist.org/packages/spryker/acl-entity-extension)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.0-8892BF.svg)](https://php.net/)

Provides SyncAPI and AsyncAPI schema files and the needed code to make the Mini-Framework an App.

## Installation

```
composer require spryker/app-kernel
```

## Documentation

[Spryker Documentation](https://academy.spryker.com/developing_with_spryker/module_guide/modules.html)

## Integration

### Configure
#### App Identifier

config/Shared/config_default.php

```
use Spryker\Shared\AppKernel\AppConstants;

$config[AppConstants::APP_IDENTIFIER] = getenv('APP_IDENTIFIER') ?: 'hello-world';
```

#### Messages

```
use Generated\Shared\Transfer\ConfigureAppTransfer;
use Generated\Shared\Transfer\DeleteAppTransfer;
use Spryker\Shared\MessageBroker\MessageBrokerConstants;
use Spryker\Shared\MessageBrokerAws\MessageBrokerAwsConstants;
use Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig;

$config[MessageBrokerConstants::MESSAGE_TO_CHANNEL_MAP] = [
    ConfigureAppTransfer::class => 'app-commands',
    DeleteAppTransfer::class => 'app-commands',
];

$config[MessageBrokerAwsConstants::CHANNEL_TO_SENDER_TRANSPORT_MAP] = [
    'app-commands' => 'http',
];

$config[MessageBrokerConstants::CHANNEL_TO_TRANSPORT_MAP] =
$config[MessageBrokerAwsConstants::CHANNEL_TO_RECEIVER_TRANSPORT_MAP] = [
    'app-commands' => MessageBrokerAwsConfig::SQS_TRANSPORT,
];
```

#### Plugins
 TBD
