<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\AppKernel\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\AppConfigBuilder;
use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
use Orm\Zed\AppKernel\Persistence\SpyAppConfigQuery;
use Spryker\Zed\AppKernel\AppKernelConfig;
use Spryker\Zed\AppKernel\Business\AppKernelFacade;
use Spryker\Zed\AppKernel\Persistence\AppKernelEntityManager;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class AppConfigHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param string $tenantIdentifier
     * @param array $appConfiguration
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\AppConfigTransfer
     */
    public function haveAppConfigForTenant(
        string $tenantIdentifier,
        array $appConfiguration = [],
        bool $isActive = false,
        string $status = AppKernelConfig::APP_STATUS_NEW,
        array $seed = []
    ): AppConfigTransfer {
        $seed[AppConfigTransfer::CONFIG] = array_merge($this->getDefaultConfigData(), $appConfiguration);
        $seed[AppConfigTransfer::TENANT_IDENTIFIER] = $tenantIdentifier;
        $seed[AppConfigTransfer::IS_ACTIVE] = $isActive;
        $seed[AppConfigTransfer::STATUS] = $status;

        $appConfigTransfer = (new AppConfigBuilder($seed))->build();

        $appKernelEntityManager = new AppKernelEntityManager();
        $appKernelEntityManager->saveConfig($appConfigTransfer);

        $this->getDataCleanupHelper()->addCleanup(function () use ($tenantIdentifier): void {
            $spyAppConfigQuery = SpyAppConfigQuery::create();
            $appConfigEntity = $spyAppConfigQuery->findOneByTenantIdentifier($tenantIdentifier);

            if ($appConfigEntity) {
                $appConfigEntity->delete();
            }
        });

        return $appConfigTransfer;
    }

    /**
     * @param array|string $expectedAppConfig
     */
    public function assertAppConfigForTenantEquals(string $tenantIdentifier, ?array $expectedAppConfig = null): void
    {
        $appKernelFacade = new AppKernelFacade();
        $appConfigTransfer = $appKernelFacade->getConfig((new AppConfigCriteriaTransfer())->setTenantIdentifier($tenantIdentifier), new AppConfigTransfer());

        $this->assertNotNull($appConfigTransfer, sprintf('Expected to find a StripeConfiguration for the Tenant "%s" but haven\'t found one.', $tenantIdentifier));

        $expectedAppConfig = $expectedAppConfig ?? $this->getDefaultConfigData();

        $this->assertSame($expectedAppConfig, $appConfigTransfer->modifiedToArray(true, true)['config']);
    }

    public function assertAppConfigForTenantIsInState(string $tenantIdentifier, string $state): void
    {
        $appKernelFacade = new AppKernelFacade();
        $appConfigTransfer = $appKernelFacade->getConfig((new AppConfigCriteriaTransfer())->setTenantIdentifier($tenantIdentifier), new AppConfigTransfer());

        $this->assertNotNull($appConfigTransfer, sprintf('Expected to find a StripeConfiguration for the Tenant "%s" but haven\'t found one.', $tenantIdentifier));

        $this->assertSame($state, $appConfigTransfer->getStatus());
    }

    public function assertAppConfigurationForTenantDoesNotExist(string $tenantIdentifier): void
    {
        $spyAppConfigQuery = SpyAppConfigQuery::create();
        $tenantStripeConfigurationEntity = $spyAppConfigQuery->findOneByTenantIdentifier($tenantIdentifier);

        $this->assertNull($tenantStripeConfigurationEntity, sprintf('Expected not to find an AppConfiguration for the Tenant "%s" but found one.', $tenantIdentifier));
    }

    public function assertAppConfigurationForTenantIsDeactivated(string $tenantIdentifier): void
    {
        $spyAppConfigQuery = SpyAppConfigQuery::create();
        $tenantStripeConfigurationEntity = $spyAppConfigQuery->findOneByTenantIdentifier($tenantIdentifier);

        $this->assertNotNull($tenantStripeConfigurationEntity, sprintf('Expected  to find an AppConfiguration for the Tenant "%s" but found none.', $tenantIdentifier));
        $this->assertFalse($tenantStripeConfigurationEntity->getIsActive(), sprintf('Expected not to have a deactivated AppConfiguration for the Tenant "%s" but found an active one.', $tenantIdentifier));
    }

    /**
     * @return array<array>
     */
    public function getAppConfigureRequestData(): array
    {
        return [
            'data' => [
                'type' => 'configuration',
                'attributes' => [
                    'configuration' => json_encode($this->getDefaultConfigData()),
                ],
            ],
        ];
    }

    protected function getDefaultConfigData(): array
    {
        return [
            'key' => 'value',
        ];
    }
}
