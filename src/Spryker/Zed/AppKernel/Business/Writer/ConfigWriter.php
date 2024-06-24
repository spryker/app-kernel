<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Business\Writer;

use Generated\Shared\Transfer\AppConfigResponseTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
use Spryker\Client\SecretsManager\Exception\MissingSecretsManagerProviderPluginException;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\AppKernel\AppKernelConfig;
use Spryker\Zed\AppKernel\Business\EncryptionConfigurator\PropelEncryptionConfiguratorInterface;
use Spryker\Zed\AppKernel\Business\MessageSender\MessageSenderInterface;
use Spryker\Zed\AppKernel\Persistence\AppKernelEntityManagerInterface;
use Spryker\Zed\AppKernel\Persistence\AppKernelRepositoryInterface;
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
     * @param array<\Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationBeforeSavePluginInterface> $configurationBeforeSavePlugins
     * @param array<\Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationAfterSavePluginInterface> $configurationAfterSavePlugins
     * @param array<\Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationBeforeDeletePluginInterface> $configurationBeforeDeletePlugin
     * @param array<\Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationAfterDeletePluginInterface> $configurationAfterDeletePlugin
     */
    public function __construct(
        protected AppKernelEntityManagerInterface $appKernelEntityManager,
        protected AppKernelRepositoryInterface $appKernelRepository,
        protected PropelEncryptionConfiguratorInterface $propelEncryptionConfigurator,
        protected MessageSenderInterface $messageSender,
        protected array $configurationBeforeSavePlugins = [],
        protected array $configurationAfterSavePlugins = [],
        protected array $configurationBeforeDeletePlugin = [],
        protected array $configurationAfterDeletePlugin = []
    ) {
    }

    public function saveConfig(AppConfigTransfer $appConfigTransfer): AppConfigResponseTransfer
    {
        try {
            $appConfigTransfer = $this->getTransactionHandler()->handleTransaction(function () use ($appConfigTransfer): \Generated\Shared\Transfer\AppConfigTransfer {
                return $this->doSaveAppConfig($appConfigTransfer);
            });

            return $this->getSuccessResponse($appConfigTransfer);
        } catch (Throwable $throwable) {
            $this->getLogger()->error(
                static::FAILED_TO_REGISTER_TENANT_MESSAGE,
                [
                    'tenantIdentifier' => $appConfigTransfer->getTenantIdentifier(),
                    'exception' => $throwable,
                ],
            );

            return $this->getFailResponse(sprintf('%s: %s', static::FAILED_TO_REGISTER_TENANT_MESSAGE, $throwable->getMessage()));
        }
    }

    protected function doSaveAppConfig(AppConfigTransfer $appConfigTransfer): AppConfigTransfer
    {
        $appConfigTransfer = $this->executeBeforePlugins($appConfigTransfer);

        $this->configurePropelEncryption($appConfigTransfer);

        if ($appConfigTransfer->getStatus() === null || $appConfigTransfer->getStatus() === '' || $appConfigTransfer->getStatus() === '0') {
            $appConfigTransfer->setStatus(AppKernelConfig::APP_STATUS_NEW);
        }

        $appConfigTransfer = $this->appKernelEntityManager->saveConfig($appConfigTransfer);

        $appConfigTransfer = $this->executeAfterPlugins($appConfigTransfer);

        return $this->messageSender->sendAppConfigUpdatedMessage($appConfigTransfer);
    }

    protected function executeBeforePlugins(AppConfigTransfer $appConfigTransfer): AppConfigTransfer
    {
        if ($appConfigTransfer->getIsActive() !== false) {
            return $this->executeConfigurationBeforeSavePlugins($appConfigTransfer);
        }

        return $this->executeConfigurationBeforeDeletePlugins($appConfigTransfer);
    }

    protected function executeAfterPlugins(AppConfigTransfer $appConfigTransfer): AppConfigTransfer
    {
        if ($appConfigTransfer->getIsActive() !== false) {
            return $this->executeConfigurationAfterSavePlugins($appConfigTransfer);
        }

        return $this->executeConfigurationAfterDeletePlugins($appConfigTransfer);
    }

    protected function executeConfigurationBeforeSavePlugins(AppConfigTransfer $appConfigTransfer): AppConfigTransfer
    {
        foreach ($this->configurationBeforeSavePlugins as $configurationBeforeSavePlugin) {
            $appConfigTransfer = $configurationBeforeSavePlugin->beforeSave($appConfigTransfer);
        }

        return $appConfigTransfer;
    }

    protected function executeConfigurationAfterSavePlugins(AppConfigTransfer $appConfigTransfer): AppConfigTransfer
    {
        foreach ($this->configurationAfterSavePlugins as $configurationAfterSavePlugin) {
            $appConfigTransfer = $configurationAfterSavePlugin->afterSave($appConfigTransfer);
        }

        return $appConfigTransfer;
    }

    protected function configurePropelEncryption(AppConfigTransfer $appConfigTransfer): void
    {
        try {
            $this->propelEncryptionConfigurator->configurePropelEncryption($appConfigTransfer->getTenantIdentifierOrFail());
        } catch (MissingSecretsManagerProviderPluginException) {
        }
    }

    protected function getSuccessResponse(AppConfigTransfer $appConfigTransfer): AppConfigResponseTransfer
    {
        $appConfigResponseTransfer = new AppConfigResponseTransfer();

        $appConfigResponseTransfer->setIsSuccessful(true)
            ->setAppConfig($appConfigTransfer);

        return $appConfigResponseTransfer;
    }

    protected function getFailResponse(string $errorMessage): AppConfigResponseTransfer
    {
        return (new AppConfigResponseTransfer())
            ->setIsSuccessful(false)
            ->setErrorMessage($errorMessage);
    }

    protected function executeConfigurationBeforeDeletePlugins(AppConfigTransfer $appConfigTransfer): AppConfigTransfer
    {
        foreach ($this->configurationBeforeDeletePlugin as $configurationBeforeDeletePlugin) {
            $configurationBeforeDeletePlugin->beforeDelete($appConfigTransfer);
        }

        return $appConfigTransfer;
    }

    protected function executeConfigurationAfterDeletePlugins(AppConfigTransfer $appConfigTransfer): AppConfigTransfer
    {
        foreach ($this->configurationAfterDeletePlugin as $configurationAfterDeletePlugin) {
            $configurationAfterDeletePlugin->afterDelete($appConfigTransfer);
        }

        return $appConfigTransfer;
    }
}
