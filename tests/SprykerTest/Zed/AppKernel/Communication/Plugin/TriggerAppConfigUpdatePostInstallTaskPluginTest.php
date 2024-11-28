<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AppKernel\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\AppConfigUpdatedTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Zed\AppKernel\AppKernelConfig;
use Spryker\Zed\AppKernel\Communication\Plugin\AppKernel\TriggerAppConfigUpdatePostInstallTaskPlugin;
use SprykerTest\Zed\AppKernel\AppKernelCommunicationTester;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AppKernel
 * @group Communication
 * @group Plugin
 * @group TriggerAppConfigUpdatePostInstallTaskPluginTest
 * Add your own group annotations below this line
 */
class TriggerAppConfigUpdatePostInstallTaskPluginTest extends Unit
{
    protected AppKernelCommunicationTester $tester;

    public function testTriggerAppConfigUpdatePostInstallTaskPluginTriggersAppConfigUpdateForAllConnectedTenants(): void
    {
        // Arrange
        $tenantIdentifier = Uuid::uuid4()->toString();
        $seed = [
            AppConfigTransfer::TENANT_IDENTIFIER => $tenantIdentifier,
            AppConfigTransfer::CONFIG => ['foo' => 'bar'],
            AppConfigTransfer::IS_ACTIVE => true,
            AppConfigTransfer::STATUS => AppKernelConfig::APP_STATUS_CONNECTED,
        ];

        $this->tester->havePersistedAppConfigTransfer($seed);

        $triggerAppConfigUpdatePostInstallTaskPlugin = new TriggerAppConfigUpdatePostInstallTaskPlugin();
        $inputMock = $this->createMock(InputInterface::class);
        $outputMock = $this->createMock(OutputInterface::class);

        // Act
        $triggerAppConfigUpdatePostInstallTaskPlugin->run($inputMock, $outputMock);

        // Assert
        $this->tester->assertMessageWasSent(AppConfigUpdatedTransfer::class);
    }

    public function testTriggerAppConfigUpdatePostInstallTaskPluginDoesNotTriggerAppConfigUpdateForNotConnectedTenants(): void
    {
        // Arrange
        $tenantIdentifier = Uuid::uuid4()->toString();
        $seed = [
            AppConfigTransfer::TENANT_IDENTIFIER => $tenantIdentifier,
            AppConfigTransfer::CONFIG => ['foo' => 'bar'],
            AppConfigTransfer::IS_ACTIVE => false,
            AppConfigTransfer::STATUS => AppKernelConfig::APP_STATUS_DISCONNECTED,
        ];

        $this->tester->havePersistedAppConfigTransfer($seed);

        $triggerAppConfigUpdatePostInstallTaskPlugin = new TriggerAppConfigUpdatePostInstallTaskPlugin();
        $inputMock = $this->createMock(InputInterface::class);
        $outputMock = $this->createMock(OutputInterface::class);

        // Act
        $triggerAppConfigUpdatePostInstallTaskPlugin->run($inputMock, $outputMock);

        // Assert
        $this->tester->assertMessageWasNotSent(AppConfigUpdatedTransfer::class);
    }
}
