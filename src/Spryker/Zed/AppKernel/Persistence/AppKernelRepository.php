<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Persistence;

use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
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
     * @throws \Spryker\Zed\AppKernel\Persistence\Exception\AppConfigNotFoundException
     */
    public function findAppConfigByCriteria(
        AppConfigCriteriaTransfer $appConfigCriteriaTransfer
    ): AppConfigTransfer {
        $appConfigEntity = $this->getFactory()
            ->createAppConfigQuery()
            ->findOneByTenantIdentifier($appConfigCriteriaTransfer->getTenantIdentifierOrFail());

        if ($appConfigEntity === null) {
            $errorMessage = 'Could not find an App configuration for the given Tenant';

            $this->getLogger()->error($errorMessage, [
                'tenantIdentifier' => $appConfigCriteriaTransfer->getTenantIdentifierOrFail(),
            ]);

            throw new AppConfigNotFoundException($errorMessage);
        }

        $appConfigMapper = $this->getFactory()->createAppConfigMapper();

        return $appConfigMapper->mapAppConfigEntityToAppConfigTransfer(
            $appConfigEntity,
            new AppConfigTransfer(),
        );
    }
}
