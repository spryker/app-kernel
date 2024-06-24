<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Persistence;

use Orm\Zed\AppKernel\Persistence\SpyAppConfigQuery;
use Spryker\Zed\AppKernel\AppKernelDependencyProvider;
use Spryker\Zed\AppKernel\Dependency\Service\AppKernelToUtilEncodingServiceInterface;
use Spryker\Zed\AppKernel\Persistence\Propel\Mapper\AppConfigMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\AppKernel\Persistence\AppKernelEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\AppKernel\Persistence\AppKernelRepositoryInterface getRepository()
 * @method \Spryker\Zed\AppKernel\AppKernelConfig getConfig()
 */
class AppKernelPersistenceFactory extends AbstractPersistenceFactory
{
    public function createAppConfigMapper(): AppConfigMapper
    {
        return new AppConfigMapper(
            $this->getUtilEncodingService(),
        );
    }

    public function createAppConfigQuery(): SpyAppConfigQuery
    {
        return SpyAppConfigQuery::create();
    }

    public function getUtilEncodingService(): AppKernelToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(AppKernelDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
