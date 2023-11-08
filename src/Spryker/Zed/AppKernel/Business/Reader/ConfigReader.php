<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Business\Reader;

use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
use Spryker\Client\SecretsManager\Exception\MissingSecretsManagerProviderPluginException;
use Spryker\Client\SecretsManager\SecretsManagerDependencyProvider;
use Spryker\Client\SecretsManagerExtension\Dependency\Plugin\SecretsManagerProviderPluginInterface;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\AppKernel\Business\EncryptionConfigurator\PropelEncryptionConfiguratorInterface;
use Spryker\Zed\AppKernel\Persistence\AppKernelRepositoryInterface;

class ConfigReader implements ConfigReaderInterface
{
    use LoggerTrait;

    /**
     * @param \Spryker\Zed\AppKernel\Persistence\AppKernelRepositoryInterface $appKernelRepository
     * @param \Spryker\Zed\AppKernel\Business\EncryptionConfigurator\PropelEncryptionConfiguratorInterface $propelEncryptionConfigurator
     */
    public function __construct(
        protected AppKernelRepositoryInterface $appKernelRepository,
        protected PropelEncryptionConfiguratorInterface $propelEncryptionConfigurator
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\AppConfigCriteriaTransfer $appConfigCriteriaTransfer
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getAppConfigByCriteria(AppConfigCriteriaTransfer $appConfigCriteriaTransfer, TransferInterface $transfer): TransferInterface
    {
        $this->configurePropelEncryption($appConfigCriteriaTransfer);

        return $this->appKernelRepository->findAppConfigByCriteria($appConfigCriteriaTransfer, $transfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AppConfigCriteriaTransfer $appConfigCriteriaTransfer
     *
     * @return void
     */
    protected function configurePropelEncryption(AppConfigCriteriaTransfer $appConfigCriteriaTransfer): void
    {
        try {
            $this->propelEncryptionConfigurator->configurePropelEncryption($appConfigCriteriaTransfer->getTenantIdentifierOrFail());
        } catch (MissingSecretsManagerProviderPluginException) {
            $this->getLogger()->warning(sprintf('There is no %s attached to %s::getSecretsManagerProviderPlugin(). This leads to unencrypted data in the database which should be avoided.', SecretsManagerProviderPluginInterface::class, SecretsManagerDependencyProvider::class));
        }
    }
}
