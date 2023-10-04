<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Business\Reader;

use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\AppKernel\Business\EncryptionConfigurator\PropelEncryptionConfiguratorInterface;
use Spryker\Zed\AppKernel\Persistence\AppKernelRepositoryInterface;

class ConfigReader implements ConfigReaderInterface
{
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
        $this->propelEncryptionConfigurator->configurePropelEncryption($appConfigCriteriaTransfer->getTenantIdentifierOrFail());

        return $this->appKernelRepository->findAppConfigByCriteria($appConfigCriteriaTransfer, $transfer);
    }
}
