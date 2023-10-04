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
use Spryker\Zed\AppKernel\Business\Reader\ConfigReader;
use Spryker\Zed\AppKernel\Business\Reader\ConfigReaderInterface;
use Spryker\Zed\AppKernel\Business\SecretsManager\SecretsManager;
use Spryker\Zed\AppKernel\Business\SecretsManager\SecretsManagerInterface;
use Spryker\Zed\AppKernel\Business\Writer\ConfigWriter;
use Spryker\Zed\AppKernel\Business\Writer\ConfigWriterInterface;
use Spryker\Zed\AppKernel\Dependency\Client\AppKernelToSecretsManagerClientInterface;
use Spryker\Zed\AppKernel\Dependency\Service\AppKernelToUtilTextServiceInterface;
use Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationAfterDeletePluginInterface;
use Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationAfterSavePluginInterface;
use Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationBeforeDeletePluginInterface;
use Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationBeforeSavePluginInterface;
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
            $this->createPropelEncryptionConfigurator(),
            $this->getConfigurationBeforeSavePlugin(),
            $this->getConfigurationAfterSavePlugin(),
        );
    }

    /**
     * @return \Spryker\Zed\AppKernel\Business\Deleter\ConfigDeleterInterface
     */
    public function createConfigDeleter(): ConfigDeleterInterface
    {
        return new ConfigDeleter(
            $this->getEntityManager(),
            $this->getConfigurationBeforeDeletePlugin(),
            $this->getConfigurationAfterDeletePlugin(),
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
     * @return \Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationBeforeSavePluginInterface|null
     */
    public function getConfigurationBeforeSavePlugin(): ?ConfigurationBeforeSavePluginInterface
    {
        return $this->getProvidedDependency(AppKernelDependencyProvider::PLUGIN_CONFIGURATION_BEFORE_SAVE_PLUGIN);
    }

    /**
     * @return \Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationAfterSavePluginInterface|null
     */
    public function getConfigurationAfterSavePlugin(): ?ConfigurationAfterSavePluginInterface
    {
        return $this->getProvidedDependency(AppKernelDependencyProvider::PLUGIN_CONFIGURATION_AFTER_SAVE_PLUGIN);
    }

    /**
     * @return \Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationBeforeDeletePluginInterface|null
     */
    public function getConfigurationBeforeDeletePlugin(): ?ConfigurationBeforeDeletePluginInterface
    {
        return $this->getProvidedDependency(AppKernelDependencyProvider::PLUGIN_CONFIGURATION_BEFORE_DELETE_PLUGIN);
    }

    /**
     * @return \Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationAfterDeletePluginInterface|null
     */
    public function getConfigurationAfterDeletePlugin(): ?ConfigurationAfterDeletePluginInterface
    {
        return $this->getProvidedDependency(AppKernelDependencyProvider::PLUGIN_CONFIGURATION_AFTER_DELETE_PLUGIN);
    }
}
