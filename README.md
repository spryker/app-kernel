# AppKernel Module
[![Latest Stable Version](https://poser.pugx.org/spryker/app-kernel/v/stable.svg)](https://packagist.org/packages/spryker/app-kernel)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.1-8892BF.svg)](https://php.net/)

Provides SyncAPI and AsyncAPI schema files and the needed code to make the Mini-Framework an App.

## Installation

```
composer require spryker/app-kernel
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

# Documentation

## Configuration
### AppIdentifier

Every App needs a unique App Identifier which needs to be configured in the `config/Shared/config_default.php` file after installation.

```
use Spryker\Shared\AppKernel\AppConstants;

$config[AppConstants::APP_IDENTIFIER] = getenv('APP_IDENTIFIER') ?: '1ba6db00-d12c-43c9-9783-936e4cded397';
```

## High-Level Architecture

[<img alt="alt_text" width="auto" src="docs/images/app-kernel-high-level-architecture.svg" />](https://docs.spryker.com/)

## Features

### Encryption
This package comes with a built-in Encryption for data that gets persisted. By default, the encryption is disabled but can be easily enabled. After installation and setup, you can configure the encryption of sensitive data.

### Enable Plugin
The encryption will be done with the help of a plugin. You can add the required plugin to the `\Pyz\Client\SecretsManager\SecretsManagerDependencyProvider::getSecretsManagerProviderPlugin()` method on the project level.

Currently, we have an AWS Plugin available for you `\Spryker\Client\SecretsManagerAws\Plugin\SecretsManager\SecretsManagerAwsProviderPlugin` for any other encryption you need to create your own plugin.

You can install the package that contains the plugin with

```
composer require spryker/secrets-manager-aws
```

### Extend the Propel schema

Add a `spy_app_kernel.schema.xml` on the project level to `src/Pyz/AppKernel/Zed/Persistence/Propel/schema` and update the columns you want to be encrypted.

Example

```
<?xml version="1.0"?>
<database xmlns="spryker:schema-01" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" name="zed" xsi:schemaLocation="spryker:schema-01 https://static.spryker.com/schema-01.xsd" namespace="Orm\Zed\AppKernel\Persistence" package="src.Orm.Zed.AppKernel.Persistence">

    <table name="spy_app_config" identifierQuoting="true">
        <behavior name="encryption">
            <parameter name="column_name_1" value="config"/>
        </behavior>
    </table>

</database>
```

Next, execute the migrations by running the docker/sdk console propel:install command, then these columns will be encrypted.

## Plugins

This package provides the following plugins

### Glue

- `\Spryker\Glue\AppKernel\Plugin\RouteProvider\AppKernelRouteProviderPlugin`

#### AppKernelRouteProviderPlugin

This plugin must be added to the `\Pyz\Glue\GlueBackendApiApplication\GlueBackendApiApplicationDependencyProvider::getRouteProviderPlugins()`.

## Extension

In most cases, the default behavior of the AppKernel will be enough. When you need to add or change behavior we provide extension points you can use to change the AppKernel.

This package provides the following extensions.

### Glue

- Configuration validation plugins
- Disconnect validation plugins

#### Configuration validation plugins

This plugin stack can be used to add your own validators for the configuration of the App. This will most likely be useful when you need to validate the passed configuration from the App Store Catalog e.g. checking against the implemented provider if credentials are valid.

You can add your own plugins to the \Pyz\Glue\AppKernel\AppKernelDependencyProvider::getRequestConfigureValidatorPlugins() method on the project level.

#### Disconnect validation plugins

This plugin stack can be used to add your own validators for the disconnect of the App. This can be used e.g. for validating if the App can be disconnected or not.

You can add your own plugins to the \Pyz\Glue\AppKernel\AppKernelDependencyProvider::getRequestDisconnectValidatorPlugins() method on the project level.

## Zed

Zed offers the following extension points

- `\Spryker\Zed\AppKernelExtension\Dependency\Plugin\AppKernelPlatformPluginInterface`
- `\Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationBeforeSavePluginInterface`
- `\Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationAfterSavePluginInterface`
- `\Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationBeforeDeletePluginInterface`
- `\Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationAfterDeletePluginInterface`

Both save and delete actions can be extended with a plugin that needs to be executed before and after the AppKernel code is executed.

### AppKernelPlatformPluginInterface

You can implement this plugin in the Apps PlatformPlugin. It enables the validation of the configuration that gets passed from the AppStoreCatalog.

### ConfigurationBeforeSavePluginInterface

You can add your plugins to the \Pyz\Zed\AppKernel\AppKernelDependencyProvider::getConfigurationBeforeSavePlugins() method on the project level.

### ConfigurationAfterSavePluginInterface

You can add your plugins to the \Pyz\Zed\AppKernel\AppKernelDependencyProvider::getConfigurationAfterSavePlugins() method on the project level.

### ConfigurationBeforeDeletePluginInterface

You can add your plugins to the \Pyz\Zed\AppKernel\AppKernelDependencyProvider::getConfigurationBeforeDeletePlugins() method on the project level.

### ConfigurationAfterDeletePluginInterface

You can add your plugins to the \Pyz\Zed\AppKernel\AppKernelDependencyProvider::getConfigurationAfterDeletePlugins() method on the project level.



