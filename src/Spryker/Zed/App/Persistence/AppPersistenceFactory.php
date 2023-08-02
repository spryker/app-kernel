<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\App\Persistence;

use Orm\Zed\App\Persistence\SpyAppConfigQuery;
use Spryker\Zed\App\Persistence\Propel\Mapper\AppConfigMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\App\Persistence\AppEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\App\Persistence\AppRepositoryInterface getRepository()
 * @method \Spryker\Zed\App\AppConfig getConfig()
 */
class AppPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\App\Persistence\Propel\Mapper\AppConfigMapper
     */
    public function createAppConfigMapper(): AppConfigMapper
    {
        return new AppConfigMapper();
    }

    /**
     * @return \Orm\Zed\App\Persistence\SpyAppConfigQuery
     */
    public function createAppConfigQuery(): SpyAppConfigQuery
    {
        return SpyAppConfigQuery::create();
    }
}
