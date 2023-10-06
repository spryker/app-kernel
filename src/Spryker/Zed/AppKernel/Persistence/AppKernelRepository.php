<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Persistence;

use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\AppKernel\Persistence\Exception\AppConfigNotFoundException;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\AppKernel\Persistence\AppKernelPersistenceFactory getFactory()
 */
class AppKernelRepository extends AbstractRepository implements AppKernelRepositoryInterface
{
    use LoggerTrait;

    /**
     * @param \Generated\Shared\Transfer\AppConfigCriteriaTransfer $appConfigCriteriaTransfer
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @throws \Spryker\Zed\AppKernel\Persistence\Exception\AppConfigNotFoundException
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function findAppConfigByCriteria(
        AppConfigCriteriaTransfer $appConfigCriteriaTransfer,
        TransferInterface $transfer
    ): TransferInterface {
        $appConfigEntity = $this->getFactory()
            ->createAppConfigQuery()
            ->findOneByTenantIdentifier($appConfigCriteriaTransfer->getTenantIdentifierOrFail());

        if (!$appConfigEntity) {
            $errorMessage = 'Could not find an App configuration for the given Tenant';

            $this->getLogger()->error($errorMessage, [
                'Tenant' => $appConfigCriteriaTransfer->getTenantIdentifierOrFail()
            ]);

            throw new AppConfigNotFoundException($errorMessage);
        }

        $appConfigMapper = $this->getFactory()->createAppConfigMapper();

        return $appConfigMapper->mapAppConfigEntityToAppConfigTransfer(
            $appConfigEntity,
            $transfer,
        );
    }
}
