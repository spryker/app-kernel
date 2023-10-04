<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel;

use Spryker\Zed\AppKernel\Dependency\Client\AppKernelToSecretsManagerClientBridge;
use Spryker\Zed\AppKernel\Dependency\Service\AppKernelToUtilEncodingServiceBridge;
use Spryker\Zed\AppKernel\Dependency\Service\AppKernelToUtilTextServiceBridge;
use Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationAfterDeletePluginInterface;
use Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationAfterSavePluginInterface;
use Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationBeforeDeletePluginInterface;
use Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationBeforeSavePluginInterface;
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
    public const PLUGIN_CONFIGURATION_BEFORE_SAVE_PLUGIN = 'APP_KERNEL:PLUGIN_CONFIGURATION_BEFORE_SAVE_PLUGIN';

    /**
     * @var string
     */
    public const PLUGIN_CONFIGURATION_AFTER_SAVE_PLUGIN = 'APP_KERNEL:PLUGIN_CONFIGURATION_AFTER_SAVE_PLUGIN';

    /**
     * @var string
     */
    public const PLUGIN_CONFIGURATION_BEFORE_DELETE_PLUGIN = 'APP_KERNEL:PLUGIN_CONFIGURATION_BEFORE_DELETE_PLUGIN';

    /**
     * @var string
     */
    public const PLUGIN_CONFIGURATION_AFTER_DELETE_PLUGIN = 'APP_KERNEL:PLUGIN_CONFIGURATION_AFTER_DELETE_PLUGIN';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addSecretsManagerClient($container);
        $container = $this->addUtilTextService($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addConfigurationBeforeSavePlugin($container);
        $container = $this->addConfigurationAfterSavePlugin($container);
        $container = $this->addConfigurationBeforeDeletePlugin($container);
        $container = $this->addConfigurationAfterDeletePlugin($container);

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
    protected function addConfigurationBeforeSavePlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_CONFIGURATION_BEFORE_SAVE_PLUGIN, function (Container $container) {
            return $this->getConfigurationBeforeSavePlugin();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationBeforeSavePluginInterface|null
     */
    protected function getConfigurationBeforeSavePlugin(): ?ConfigurationBeforeSavePluginInterface
    {
        return null;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConfigurationAfterSavePlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_CONFIGURATION_AFTER_SAVE_PLUGIN, function (Container $container) {
            return $this->getConfigurationAfterSavePlugin();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationAfterSavePluginInterface|null
     */
    protected function getConfigurationAfterSavePlugin(): ?ConfigurationAfterSavePluginInterface
    {
        return null;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConfigurationBeforeDeletePlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_CONFIGURATION_BEFORE_DELETE_PLUGIN, function (Container $container) {
            return $this->getConfigurationBeforeDeletePlugin();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationBeforeDeletePluginInterface|null
     */
    protected function getConfigurationBeforeDeletePlugin(): ?ConfigurationBeforeDeletePluginInterface
    {
        return null;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConfigurationAfterDeletePlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_CONFIGURATION_AFTER_DELETE_PLUGIN, function (Container $container) {
            return $this->getConfigurationAfterDeletePlugin();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationAfterDeletePluginInterface|null
     */
    protected function getConfigurationAfterDeletePlugin(): ?ConfigurationAfterDeletePluginInterface
    {
        return null;
    }
}
