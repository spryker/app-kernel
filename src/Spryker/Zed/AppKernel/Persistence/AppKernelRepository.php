<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Persistence;

use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
use Orm\Zed\AppKernel\Persistence\Map\SpyAppConfigTableMap;
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
        $spyAppConfigQuery = $this->getFactory()->createAppConfigQuery();

        if ($appConfigCriteriaTransfer->getIsActive() !== null) {
            $spyAppConfigQuery->filterByIsActive($appConfigCriteriaTransfer->getIsActive());
        }

        $appConfigEntity = $spyAppConfigQuery->findOneByTenantIdentifier($appConfigCriteriaTransfer->getTenantIdentifierOrFail());

        if ($appConfigEntity === null) {
            $errorMessage = 'Could not find an App configuration for the given Tenant';

            throw new AppConfigNotFoundException($errorMessage);
        }

        $appConfigMapper = $this->getFactory()->createAppConfigMapper();

        return $appConfigMapper->mapAppConfigEntityToAppConfigTransfer(
            $appConfigEntity,
            new AppConfigTransfer(),
        );
    }

    /**
     * @return array<mixed, string>
     */
    public function getConnectedTenantIdentifiers(): array
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $connectedTenantIdentifierCollection */
        $connectedTenantIdentifierCollection = $this->getFactory()
            ->createAppConfigQuery()
            ->filterByIsActive(true)
            ->filterByStatus_In([SpyAppConfigTableMap::COL_STATUS_NEW, SpyAppConfigTableMap::COL_STATUS_CONNECTED])
            ->select(AppConfigTransfer::TENANT_IDENTIFIER)
            ->find();

        /** @var array<mixed, string> $connectedTenantIdentifiers */
        $connectedTenantIdentifiers = $connectedTenantIdentifierCollection->toArray();

        return $connectedTenantIdentifiers;
    }
}
