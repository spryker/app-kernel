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
use Spryker\Shared\AppKernel\AppKernelConstants;

$config[AppKernelConstants::APP_IDENTIFIER] = getenv('APP_IDENTIFIER') ?: 'hello-world';
$config[AppKernelConstants::OPEN_API_SCHEMA_PATH] = 'path/to/your/openApiSchema.yml';
```

### Validating Requests against the OpenAPI Schema

Low level validation can be done by using the `Spryker\Zed\AppKernel\Communication\Plugin\OpenApiSchemaValidatorPlugin` plugin. When this plugin is added to the GlueApplicationDependencyProvider all API requests against this App will be validated against the defined OpenAPI schema.

To enable this, you need to have a well-defined OpenAPI schema file, and you need to add the `OpenApiSchemaValidatorPlugin` plugin to the `getRestApplicationPlugins` method in your GlueApplicationDependencyProvider.

```php
use Spryker\Zed\AppKernel\Communication\Plugin\OpenApiSchemaValidatorPlugin;

...

    protected function getRestApplicationPlugins(): array
    {
        return [
            new OpenApiSchemaValidatorPlugin(),
        ];
    }

...
```

Pay intention that this will be a hard validation that gets executed before any other code from your App gets executed. If the validation fails, the request will be rejected with a 400 Bad Request response with a proper message that explains what exactly is wrong in the request.

Make sure you have tests for your API.

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
