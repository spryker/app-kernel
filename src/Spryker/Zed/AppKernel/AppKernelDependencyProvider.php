<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel;

use Spryker\Zed\AppKernel\Dependency\Client\AppKernelToSecretsManagerClientBridge;
use Spryker\Zed\AppKernel\Dependency\Facade\AppKernelToMessageBrokerFacadeBridge;
use Spryker\Zed\AppKernel\Dependency\Facade\AppKernelToMessageBrokerFacadeInterface;
use Spryker\Zed\AppKernel\Dependency\Service\AppKernelToUtilEncodingServiceBridge;
use Spryker\Zed\AppKernel\Dependency\Service\AppKernelToUtilTextServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\AppKernel\AppKernelConfig getConfig()
 */
class AppKernelDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_MESSAGE_BROKER = 'APP_KERNEL:FACADE_MESSAGE_BROKER';

    /**
     * @var string
     */
    public const CLIENT_SECRETS_MANAGER = 'APP_KERNEL:CLIENT_SECRETS_MANAGER';

    /**
     * @var string
     */
    public const SERVICE_UTIL_TEXT = 'APP_KERNEL:UTIL_TEXT_SERVICE';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'APP_KERNEL:SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const PLUGIN_CONFIGURATION_BEFORE_SAVE_PLUGINS = 'APP_KERNEL:PLUGIN_CONFIGURATION_BEFORE_SAVE_PLUGINS';

    /**
     * @var string
     */
    public const PLUGIN_CONFIGURATION_AFTER_SAVE_PLUGINS = 'APP_KERNEL:PLUGIN_CONFIGURATION_AFTER_SAVE_PLUGINS';

    /**
     * @var string
     */
    public const PLUGIN_CONFIGURATION_BEFORE_DELETE_PLUGINS = 'APP_KERNEL:PLUGIN_CONFIGURATION_BEFORE_DELETE_PLUGINS';

    /**
     * @var string
     */
    public const PLUGIN_CONFIGURATION_AFTER_DELETE_PLUGINS = 'APP_KERNEL:PLUGIN_CONFIGURATION_AFTER_DELETE_PLUGINS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addMessageBrokerFacade($container);
        $container = $this->addSecretsManagerClient($container);
        $container = $this->addUtilTextService($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addConfigurationBeforeSavePlugins($container);
        $container = $this->addConfigurationAfterSavePlugins($container);
        $container = $this->addConfigurationBeforeDeletePlugins($container);
        $container = $this->addConfigurationAfterDeletePlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMessageBrokerFacade(Container $container): Container
    {
        $container->set(static::FACADE_MESSAGE_BROKER, static function (Container $container): AppKernelToMessageBrokerFacadeInterface {
            return new AppKernelToMessageBrokerFacadeBridge($container->getLocator()->messageBroker()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSecretsManagerClient(Container $container): Container
    {
        $container->set(static::CLIENT_SECRETS_MANAGER, function (Container $container) {
            return new AppKernelToSecretsManagerClientBridge($container->getLocator()->secretsManager()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilTextService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_TEXT, function (Container $container) {
            return new AppKernelToUtilTextServiceBridge($container->getLocator()->utilText()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new AppKernelToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConfigurationBeforeSavePlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_CONFIGURATION_BEFORE_SAVE_PLUGINS, function (Container $container) {
            return $this->getConfigurationBeforeSavePlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationBeforeSavePluginInterface>
     */
    protected function getConfigurationBeforeSavePlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConfigurationAfterSavePlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_CONFIGURATION_AFTER_SAVE_PLUGINS, function (Container $container) {
            return $this->getConfigurationAfterSavePlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationAfterSavePluginInterface>
     */
    protected function getConfigurationAfterSavePlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConfigurationBeforeDeletePlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_CONFIGURATION_BEFORE_DELETE_PLUGINS, function (Container $container) {
            return $this->getConfigurationBeforeDeletePlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationBeforeDeletePluginInterface>
     */
    protected function getConfigurationBeforeDeletePlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConfigurationAfterDeletePlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_CONFIGURATION_AFTER_DELETE_PLUGINS, function (Container $container) {
            return $this->getConfigurationAfterDeletePlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationAfterDeletePluginInterface>
     */
    protected function getConfigurationAfterDeletePlugins(): array
    {
        return [];
    }
}
