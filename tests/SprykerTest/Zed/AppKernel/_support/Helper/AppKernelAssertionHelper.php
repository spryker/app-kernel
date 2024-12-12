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
            'Expected to have exactly no persisted configuration for tenant identifier "%s" but found "%d".',
            $tenantIdentifier,
            count($appConfigEntities),
        ));
    }

    /**
     * @param string $tenantIdentifier
     *
     * @return void
     */
    public function assertAppConfigIsActivated(string $tenantIdentifier): void
    {
        $appConfigEntities = SpyAppConfigQuery::create()
            ->filterByTenantIdentifier($tenantIdentifier)
            ->find()
            ->toArray();

        $this->assertSame(1, count($appConfigEntities), sprintf(
            'Expected to have one persisted configurations for tenant identifier "%s" but found "%d".',
            $tenantIdentifier,
            count($appConfigEntities),
        ));

        $this->assertTrue($appConfigEntities[0]['IsActive'] === true, sprintf(
            'Expected to have the configuration for tenant identifier "%s" activated but it is deactivated.',
            $tenantIdentifier,
        ));
    }

    /**
     * @param string $tenantIdentifier
     *
     * @return void
     */
    public function assertAppConfigStatus(string $tenantIdentifier, string $expectedStatus): void
    {
        $appConfigEntities = SpyAppConfigQuery::create()
            ->filterByTenantIdentifier($tenantIdentifier)
            ->find()
            ->toArray();

        $this->assertSame(1, count($appConfigEntities), sprintf(
            'Expected to have one persisted configurations for tenant identifier "%s" but found "%d".',
            $tenantIdentifier,
            count($appConfigEntities),
        ));

        $this->assertSame($expectedStatus, $appConfigEntities[0]['Status'], sprintf(
            'Expected to have the configuration for tenant identifier "%s" in status "%s" but it is in "%s".',
            $tenantIdentifier,
            $expectedStatus,
            $appConfigEntities[0]['Status'],
        ));
    }

    /**
     * @param string $tenantIdentifier
     * @param array<string, string> $expectedConfig
     *
     * @return void
     */
    public function assertPersistedAppConfig(string $tenantIdentifier, array $expectedConfig): void
    {
        $appConfigEntities = SpyAppConfigQuery::create()
            ->filterByTenantIdentifier($tenantIdentifier)
            ->find()
            ->toArray();

        $this->assertSame(1, count($appConfigEntities), sprintf(
            'Expected to have one persisted configurations for tenant identifier "%s" but found "%d".',
            $tenantIdentifier,
            count($appConfigEntities),
        ));

        $jsonEncodedExpectedConfig = json_encode($expectedConfig);
        $this->assertSame($jsonEncodedExpectedConfig, $appConfigEntities[0]['Config'], sprintf(
            'Expected to have the same configuration for tenant identifier "%s" but it is different. Expected: %s given: %s',
            $tenantIdentifier,
            $jsonEncodedExpectedConfig,
            $appConfigEntities[0]['Config'],
        ));
    }

    /**
     * @param string $tenantIdentifier
     *
     * @return void
     */
    public function assertAppConfigIsDeactivated(string $tenantIdentifier): void
    {
        $appConfigEntities = SpyAppConfigQuery::create()
            ->filterByTenantIdentifier($tenantIdentifier)
            ->find()
            ->toArray();

        $this->assertSame(1, count($appConfigEntities), sprintf(
            'Expected to have one persisted configurations for tenant identifier "%s" but found "%d".',
            $tenantIdentifier,
            count($appConfigEntities),
        ));

        $this->assertTrue($appConfigEntities[0]['IsActive'] === false, sprintf(
            'Expected to have the configuration for tenant identifier "%s" deactivated but it is activated.',
            $tenantIdentifier,
        ));
    }
}
