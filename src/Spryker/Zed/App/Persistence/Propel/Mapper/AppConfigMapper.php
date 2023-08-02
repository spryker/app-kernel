<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\App\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\AppConfigTransfer;
use Orm\Zed\App\Persistence\SpyAppConfig;

class AppConfigMapper
{
    /**
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     * @param \Orm\Zed\App\Persistence\SpyAppConfig $appConfigEntity
     *
     * @return \Orm\Zed\App\Persistence\SpyAppConfig
     */
    public function mapAppConfigTransferToAppConfigEntity(
        AppConfigTransfer $appConfigTransfer,
        SpyAppConfig $appConfigEntity
    ): SpyAppConfig {
        return $appConfigEntity->fromArray($appConfigTransfer->modifiedToArray());
    }
}
