<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Business\Reader;

use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
use Spryker\Client\SecretsManager\Exception\MissingSecretsManagerProviderPluginException;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\AppKernel\Business\EncryptionConfigurator\PropelEncryptionConfiguratorInterface;
use Spryker\Zed\AppKernel\Persistence\AppKernelRepositoryInterface;

class ConfigReader implements ConfigReaderInterface
{
    use LoggerTrait;

    public function __construct(
        protected AppKernelRepositoryInterface $appKernelRepository,
        protected PropelEncryptionConfiguratorInterface $propelEncryptionConfigurator
    ) {
    }

    public function getAppConfigByCriteria(
        AppConfigCriteriaTransfer $appConfigCriteriaTransfer
    ): AppConfigTransfer {
        $this->configurePropelEncryption($appConfigCriteriaTransfer);

        return $this->appKernelRepository->findAppConfigByCriteria($appConfigCriteriaTransfer);
    }

    protected function configurePropelEncryption(AppConfigCriteriaTransfer $appConfigCriteriaTransfer): void
    {
        try {
            $this->propelEncryptionConfigurator->configurePropelEncryption($appConfigCriteriaTransfer->getTenantIdentifierOrFail());
        } catch (MissingSecretsManagerProviderPluginException) {
        }
    }
}
