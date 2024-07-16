<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Business;

use Spryker\Zed\AppKernel\AppKernelDependencyProvider;
use Spryker\Zed\AppKernel\Business\Configuration\ConfigurationValidator;
use Spryker\Zed\AppKernel\Business\EncryptionConfigurator\PropelEncryptionConfigurator;
use Spryker\Zed\AppKernel\Business\EncryptionConfigurator\PropelEncryptionConfiguratorInterface;
use Spryker\Zed\AppKernel\Business\MessageSender\MessageSender;
use Spryker\Zed\AppKernel\Business\MessageSender\MessageSenderInterface;
use Spryker\Zed\AppKernel\Business\Reader\ConfigReader;
use Spryker\Zed\AppKernel\Business\Reader\ConfigReaderInterface;
use Spryker\Zed\AppKernel\Business\SecretsManager\SecretsManager;
use Spryker\Zed\AppKernel\Business\SecretsManager\SecretsManagerInterface;
use Spryker\Zed\AppKernel\Business\Writer\ConfigWriter;
use Spryker\Zed\AppKernel\Business\Writer\ConfigWriterInterface;
use Spryker\Zed\AppKernel\Dependency\Client\AppKernelToSecretsManagerClientInterface;
use Spryker\Zed\AppKernel\Dependency\Facade\AppKernelToMessageBrokerFacadeInterface;
use Spryker\Zed\AppKernel\Dependency\Service\AppKernelToUtilEncodingServiceInterface;
use Spryker\Zed\AppKernel\Dependency\Service\AppKernelToUtilTextServiceInterface;
use Spryker\Zed\AppKernelExtension\Dependency\Plugin\AppKernelPlatformPluginInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\AppKernel\Persistence\AppKernelEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\AppKernel\Persistence\AppKernelRepositoryInterface getRepository()
 * @method \Spryker\Zed\AppKernel\AppKernelConfig getConfig()
 */
class AppKernelBusinessFactory extends AbstractBusinessFactory
{
    public function createConfigurationValidator(): ConfigurationValidator
    {
        return new ConfigurationValidator($this->getPlatformPlugin(), $this->getUtilEncodingService());
    }

    public function createConfigWriter(): ConfigWriterInterface
    {
        return new ConfigWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createPropelEncryptionConfigurator(),
            $this->createMessageSender(),
            $this->getConfigurationBeforeSavePlugins(),
            $this->getConfigurationAfterSavePlugins(),
            $this->getConfigurationBeforeDeletePlugins(),
            $this->getConfigurationAfterDeletePlugins(),
        );
    }

    public function createConfigReader(): ConfigReaderInterface
    {
        return new ConfigReader(
            $this->getRepository(),
            $this->createPropelEncryptionConfigurator(),
        );
    }

    public function createPropelEncryptionConfigurator(): PropelEncryptionConfiguratorInterface
    {
        return new PropelEncryptionConfigurator(
            $this->createSecretsManager(),
        );
    }

    public function createSecretsManager(): SecretsManagerInterface
    {
        return new SecretsManager(
            $this->getSecretsManagerClient(),
            $this->getUtilTextService(),
        );
    }

    public function createMessageSender(): MessageSenderInterface
    {
        return new MessageSender($this->getMessageBrokerFacade(), $this->getConfig());
    }

    public function getPlatformPlugin(): AppKernelPlatformPluginInterface
    {
        return $this->getProvidedDependency(AppKernelDependencyProvider::PLUGIN_PLATFORM);
    }

    public function getSecretsManagerClient(): AppKernelToSecretsManagerClientInterface
    {
        return $this->getProvidedDependency(AppKernelDependencyProvider::CLIENT_SECRETS_MANAGER);
    }

    public function getUtilTextService(): AppKernelToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(AppKernelDependencyProvider::SERVICE_UTIL_TEXT);
    }

    public function getUtilEncodingService(): AppKernelToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(AppKernelDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return array<\Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationBeforeSavePluginInterface>
     */
    public function getConfigurationBeforeSavePlugins(): array
    {
        return $this->getProvidedDependency(AppKernelDependencyProvider::PLUGIN_CONFIGURATION_BEFORE_SAVE_PLUGINS);
    }

    /**
     * @return array<\Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationAfterSavePluginInterface>
     */
    public function getConfigurationAfterSavePlugins(): array
    {
        return $this->getProvidedDependency(AppKernelDependencyProvider::PLUGIN_CONFIGURATION_AFTER_SAVE_PLUGINS);
    }

    /**
     * @return array<\Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationBeforeDeletePluginInterface>
     */
    public function getConfigurationBeforeDeletePlugins(): array
    {
        return $this->getProvidedDependency(AppKernelDependencyProvider::PLUGIN_CONFIGURATION_BEFORE_DELETE_PLUGINS);
    }

    /**
     * @return array<\Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationAfterDeletePluginInterface>
     */
    public function getConfigurationAfterDeletePlugins(): array
    {
        return $this->getProvidedDependency(AppKernelDependencyProvider::PLUGIN_CONFIGURATION_AFTER_DELETE_PLUGINS);
    }

    public function getMessageBrokerFacade(): AppKernelToMessageBrokerFacadeInterface
    {
        return $this->getProvidedDependency(AppKernelDependencyProvider::FACADE_MESSAGE_BROKER);
    }
}
