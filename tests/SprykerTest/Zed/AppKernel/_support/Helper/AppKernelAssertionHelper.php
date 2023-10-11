<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AppKernel\Helper;

use Codeception\Module;
use Orm\Zed\AppKernel\Persistence\SpyAppConfigQuery;

class AppKernelAssertionHelper extends Module
{
    /**
     * @param string $tenantIdentifier
     *
     * @return void
     */
    public function assertAppConfigIsPersisted(string $tenantIdentifier): void
    {
        $appConfigEntities = SpyAppConfigQuery::create()
            ->filterByTenantIdentifier($tenantIdentifier)
            ->find()
            ->toArray();

        $this->assertSame(1, count($appConfigEntities), sprintf(
            'Expected to have exactly 1 persisted configuration for tenant identifier "%s" but found "%d".',
            $tenantIdentifier,
            count($appConfigEntities),
        ));
    }

    /**
     * @param string $tenantIdentifier
     *
     * @return void
     */
    public function assertAppConfigIsNotPersisted(string $tenantIdentifier): void
    {
        $appConfigEntities = SpyAppConfigQuery::create()
            ->filterByTenantIdentifier($tenantIdentifier)
            ->find()
            ->toArray();

        $this->assertSame(0, count($appConfigEntities), sprintf(
            'Expected to have no persisted configurations for tenant identifier "%s" but found "%d".',
            $tenantIdentifier,
            count($appConfigEntities),
        ));
    }
}
