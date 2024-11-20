# AppKernel Module
[![Latest Stable Version](https://poser.pugx.org/spryker/app-kernel/v/stable.svg)](https://packagist.org/packages/spryker/app-kernel)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.2-8892BF.svg)](https://php.net/)

Provides SyncAPI and AsyncAPI schema files and the needed code to make the Mini-Framework an App.

## Installation

```
composer require spryker/app-kernel
```

### Configure

#### App Identifier

config/Shared/config_default.php

```
use Spryker\Shared\AppKernel\AppConstants;

$config[AppConstants::APP_IDENTIFIER] = getenv('APP_IDENTIFIER') ?: 'hello-world';
```

### Testing the AppKernel

You can test the AppKernel as usual with Codeception. Before that you need to run some commands:

```
composer setup
```

With these commands you've set up the AppKernel and can start the tests

```
vendor/bin/codecept build
vendor/bin/codecept run
```
