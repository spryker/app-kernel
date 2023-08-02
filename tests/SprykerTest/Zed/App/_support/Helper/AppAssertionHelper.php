<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\App\Helper;

use Codeception\Module;
use Orm\Zed\App\Persistence\SpyAppConfigQuery;

class AppAssertionHelper extends Module
{
    /**
     * @param string $storeReference
     *
     * @return void
     */
    public function assertAppConfigIsPersisted(string $storeReference): void
    {
        $appConfigEntities = SpyAppConfigQuery::create()
            ->filterByStoreReference($storeReference)
            ->find()
            ->toArray();

        $this->assertSame(1, count($appConfigEntities), sprintf(
            'Expected to have exactly 1 persisted configuration for store reference "%s" but found "%d".',
            $storeReference,
            count($appConfigEntities),
        ));
    }

    /**
     * @param string $storeReference
     *
     * @return void
     */
    public function assertAppConfigIsNotPersisted(string $storeReference): void
    {
        $appConfigEntities = SpyAppConfigQuery::create()
            ->filterByStoreReference($storeReference)
            ->find()
            ->toArray();

        $this->assertSame(0, count($appConfigEntities), sprintf(
            'Expected to have no persisted configurations for store reference "%s" but found "%d".',
            $storeReference,
            count($appConfigEntities),
        ));
    }
}
