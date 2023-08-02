<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\App\Persistence;

use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
use Orm\Zed\App\Persistence\SpyAppConfig;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\App\Persistence\AppPersistenceFactory getFactory()
 */
class AppEntityManager extends AbstractEntityManager implements AppEntityManagerInterface
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
            ->findOneByStoreReference($appConfigTransfer->getStoreReferenceOrFail());

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
            ->filterByStoreReference($appConfigCriteriaTransfer->getStoreReferenceOrFail())
            ->delete();
    }
}
