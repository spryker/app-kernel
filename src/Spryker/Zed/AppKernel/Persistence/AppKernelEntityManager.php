<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Persistence;

use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
use Orm\Zed\AppKernel\Persistence\SpyAppConfig;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\AppKernel\Persistence\AppKernelPersistenceFactory getFactory()
 */
class AppKernelEntityManager extends AbstractEntityManager implements AppKernelEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AppConfigTransfer
     */
    public function saveConfig(AppConfigTransfer $appConfigTransfer): AppConfigTransfer
    {
        $appConfigEntity = $this->getFactory()
            ->createAppConfigQuery()
            ->findOneByTenantIdentifier($appConfigTransfer->getTenantIdentifierOrFail());

        if (!$appConfigEntity) {
            $appConfigEntity = new SpyAppConfig();
        }

        $appConfigMapper = $this->getFactory()->createAppConfigMapper();

        $appConfigEntity = $appConfigMapper->mapAppConfigTransferToAppConfigEntity(
            $appConfigTransfer,
            $appConfigEntity,
        );

        $appConfigEntity->save();

        return $appConfigTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AppConfigCriteriaTransfer $appConfigCriteriaTransfer
     *
     * @return int Affected number of deleted rows
     */
    public function deleteConfig(AppConfigCriteriaTransfer $appConfigCriteriaTransfer): int
    {
        $appConfigQuery = $this->getFactory()->createAppConfigQuery();

        return $appConfigQuery
            ->filterByTenantIdentifier($appConfigCriteriaTransfer->getTenantIdentifierOrFail())
            ->delete();
    }
}
