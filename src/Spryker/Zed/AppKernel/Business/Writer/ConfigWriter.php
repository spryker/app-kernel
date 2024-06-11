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
     * @param \Spryker\Zed\AppKernel\Persistence\AppKernelEntityManagerInterface $appEntityManager
     * @param \Spryker\Zed\AppKernel\Persistence\AppKernelRepositoryInterface $appKernelRepository
     * @param \Spryker\Zed\AppKernel\Business\EncryptionConfigurator\PropelEncryptionConfiguratorInterface $propelEncryptionConfigurator
     * @param array<\Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationBeforeSavePluginInterface> $configurationBeforeSavePlugins
     * @param array<\Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationAfterSavePluginInterface> $configurationAfterSavePlugins
     */
    public function __construct(
        protected AppKernelEntityManagerInterface $appEntityManager,
        protected AppKernelRepositoryInterface $appKernelRepository,
        protected PropelEncryptionConfiguratorInterface $propelEncryptionConfigurator,
        protected array $configurationBeforeSavePlugins = [],
        protected array $configurationAfterSavePlugins = []
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
            $this->getLogger()->error(
                static::FAILED_TO_REGISTER_TENANT_MESSAGE,
                [
                    'tenantIdentifier' => $appConfigTransfer->getTenantIdentifier(),
                    'exception' => $throwable,
                ]
            );

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
        $appConfigTransfer = $this->executeConfigurationBeforeSavePlugins($appConfigTransfer);

        $this->configurePropelEncryption($appConfigTransfer);

        if (!$appConfigTransfer->getStatus()) {
            $appConfigTransfer->setStatus(AppKernelConfig::APP_STATUS_NEW);
        }
        $appConfigTransfer = $this->appEntityManager->saveConfig($appConfigTransfer);

        return $this->executeConfigurationAfterSavePlugins($appConfigTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AppConfigTransfer
     */
    protected function executeConfigurationBeforeSavePlugins(AppConfigTransfer $appConfigTransfer): AppConfigTransfer
    {
        foreach ($this->configurationBeforeSavePlugins as $configurationBeforeSavePlugin) {
            $appConfigTransfer = $configurationBeforeSavePlugin->beforeSave($appConfigTransfer);
        }

        return $appConfigTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AppConfigTransfer
     */
    protected function executeConfigurationAfterSavePlugins(AppConfigTransfer $appConfigTransfer): AppConfigTransfer
    {
        foreach ($this->configurationAfterSavePlugins as $configurationAfterSavePlugin) {
            $appConfigTransfer = $configurationAfterSavePlugin->afterSave($appConfigTransfer);
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
