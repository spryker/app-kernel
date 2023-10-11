<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Business\Writer;

use Generated\Shared\Transfer\AppConfigResponseTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
use Spryker\Client\SecretsManager\Exception\MissingSecretsManagerProviderPluginException;
use Spryker\Client\SecretsManager\SecretsManagerDependencyProvider;
use Spryker\Client\SecretsManagerExtension\Dependency\Plugin\SecretsManagerProviderPluginInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\AppKernel\Business\EncryptionConfigurator\PropelEncryptionConfiguratorInterface;
use Spryker\Zed\AppKernel\Persistence\AppKernelEntityManagerInterface;
use Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationAfterSavePluginInterface;
use Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationBeforeSavePluginInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Throwable;

class ConfigWriter implements ConfigWriterInterface
{
    use LoggerTrait;
    use TransactionTrait;

    /**
     * @var string
     */
    protected const FAILED_TO_REGISTER_TENANT_MESSAGE = 'Tenant registration failed';

    /**
     * @param \Spryker\Zed\AppKernel\Persistence\AppKernelEntityManagerInterface $appEntityManager
     * @param \Spryker\Zed\AppKernel\Business\EncryptionConfigurator\PropelEncryptionConfiguratorInterface $propelEncryptionConfigurator
     * @param \Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationBeforeSavePluginInterface|null $configurationBeforeSavePlugin
     * @param \Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationAfterSavePluginInterface|null $configurationAfterSavePlugin
     */
    public function __construct(
        protected AppKernelEntityManagerInterface $appEntityManager,
        protected PropelEncryptionConfiguratorInterface $propelEncryptionConfigurator,
        protected ?ConfigurationBeforeSavePluginInterface $configurationBeforeSavePlugin = null,
        protected ?ConfigurationAfterSavePluginInterface $configurationAfterSavePlugin = null
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AppConfigResponseTransfer
     */
    public function saveConfig(AppConfigTransfer $appConfigTransfer): AppConfigResponseTransfer
    {
        try {
            $appConfigTransfer = $this->getTransactionHandler()->handleTransaction(function () use ($appConfigTransfer) {
                return $this->doSaveAppConfig($appConfigTransfer);
            });

            return $this->getSuccessResponse($appConfigTransfer);
        } catch (Throwable $throwable) {
            $this->getLogger()->error(static::FAILED_TO_REGISTER_TENANT_MESSAGE, ['exception' => $throwable]);

            return $this->getFailResponse(sprintf('%s: %s', static::FAILED_TO_REGISTER_TENANT_MESSAGE, $throwable->getMessage()));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AppConfigTransfer
     */
    protected function doSaveAppConfig(AppConfigTransfer $appConfigTransfer): AppConfigTransfer
    {
        if ($this->configurationBeforeSavePlugin) {
            $appConfigTransfer = $this->configurationBeforeSavePlugin->beforeSave($appConfigTransfer);
        }

        $this->configurePropelEncryption($appConfigTransfer);

        $appConfigTransfer = $this->appEntityManager->saveConfig($appConfigTransfer);

        if ($this->configurationAfterSavePlugin) {
            $appConfigTransfer = $this->configurationAfterSavePlugin->afterSave($appConfigTransfer);
        }

        return $appConfigTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     *
     * @return void
     */
    protected function configurePropelEncryption(AppConfigTransfer $appConfigTransfer): void
    {
        try {
            $this->propelEncryptionConfigurator->configurePropelEncryption($appConfigTransfer->getTenantIdentifierOrFail());
        } catch (MissingSecretsManagerProviderPluginException) {
            $this->getLogger()->warning(sprintf('There is no %s attached to %s::getSecretsManagerProviderPlugin(). This leads to unencrypted data in the database which should be avoided.', SecretsManagerProviderPluginInterface::class, SecretsManagerDependencyProvider::class));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AppConfigResponseTransfer
     */
    protected function getSuccessResponse(AppConfigTransfer $appConfigTransfer): AppConfigResponseTransfer
    {
        $appResponseTransfer = new AppConfigResponseTransfer();

        $appResponseTransfer->setIsSuccessful(true)
            ->setAppConfig($appConfigTransfer);

        return $appResponseTransfer;
    }

    /**
     * @param string $errorMessage
     *
     * @return \Generated\Shared\Transfer\AppConfigResponseTransfer
     */
    protected function getFailResponse(string $errorMessage): AppConfigResponseTransfer
    {
        return (new AppConfigResponseTransfer())
            ->setIsSuccessful(false)
            ->setErrorMessage($errorMessage);
    }
}
