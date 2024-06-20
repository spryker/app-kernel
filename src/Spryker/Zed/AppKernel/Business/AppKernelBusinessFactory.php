<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Business;

use Spryker\Zed\AppKernel\AppKernelDependencyProvider;
use Spryker\Zed\AppKernel\Business\Deleter\ConfigDeleter;
use Spryker\Zed\AppKernel\Business\Deleter\ConfigDeleterInterface;
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
use Spryker\Zed\AppKernel\Dependency\Service\AppKernelToUtilTextServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\AppKernel\Persistence\AppKernelEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\AppKernel\Persistence\AppKernelRepositoryInterface getRepository()
 * @method \Spryker\Zed\AppKernel\AppKernelConfig getConfig()
 */
class AppKernelBusinessFactory extends AbstractBusinessFactory
{
 /**
  * @return \Spryker\Zed\AppKernel\Business\Writer\ConfigWriterInterface
  */
    public function createConfigWriter(): ConfigWriterInterface
    {
        return new ConfigWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createPropelEncryptionConfigurator(),
            $this->getConfigurationBeforeSavePlugins(),
            $this->getConfigurationAfterSavePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\AppKernel\Business\Deleter\ConfigDeleterInterface
     */
    public function createConfigDeleter(): ConfigDeleterInterface
    {
        return new ConfigDeleter(
            $this->getEntityManager(),
            $this->getConfigurationBeforeDeletePlugins(),
            $this->getConfigurationAfterDeletePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\AppKernel\Business\Reader\ConfigReaderInterface
     */
    public function createConfigReader(): ConfigReaderInterface
    {
        return new ConfigReader(
            $this->getRepository(),
            $this->createPropelEncryptionConfigurator(),
        );
    }

    /**
     * @return \Spryker\Zed\AppKernel\Business\EncryptionConfigurator\PropelEncryptionConfiguratorInterface
     */
    public function createPropelEncryptionConfigurator(): PropelEncryptionConfiguratorInterface
    {
        return new PropelEncryptionConfigurator(
            $this->createSecretsManager(),
        );
    }

    /**
     * @return \Spryker\Zed\AppKernel\Business\SecretsManager\SecretsManagerInterface
     */
    public function createSecretsManager(): SecretsManagerInterface
    {
        return new SecretsManager(
            $this->getSecretsManagerClient(),
            $this->getTextServiceUtil(),
        );
    }

    /**
     * @return \Spryker\Zed\AppKernel\Business\MessageSender\MessageSenderInterface
     */
    public function createMessageSender(): MessageSenderInterface
    {
        return new MessageSender($this->getMessageBrokerFacade(), $this->getConfig());
    }

    /**
     * @return \Spryker\Zed\AppKernel\Dependency\Client\AppKernelToSecretsManagerClientInterface
     */
    public function getSecretsManagerClient(): AppKernelToSecretsManagerClientInterface
    {
        return $this->getProvidedDependency(AppKernelDependencyProvider::CLIENT_SECRETS_MANAGER);
    }

    /**
     * @return \Spryker\Zed\AppKernel\Dependency\Service\AppKernelToUtilTextServiceInterface
     */
    public function getTextServiceUtil(): AppKernelToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(AppKernelDependencyProvider::SERVICE_UTIL_TEXT);
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

    /**
     * @return \Spryker\Zed\AppKernel\Dependency\Facade\AppKernelToMessageBrokerFacadeInterface
     */
    public function getMessageBrokerFacade(): AppKernelToMessageBrokerFacadeInterface
    {
        return $this->getProvidedDependency(AppKernelDependencyProvider::FACADE_MESSAGE_BROKER);
    }
}
