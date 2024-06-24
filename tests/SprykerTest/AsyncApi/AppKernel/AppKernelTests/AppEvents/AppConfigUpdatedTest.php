<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\AsyncApi\AppKernel\AppKernelTests\AppEvents;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\AppConfigUpdatedTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Shared\AppKernel\AppKernelConstants;
use SprykerTest\AsyncApi\AppKernel\AppKernelAsyncApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group AsyncApi
 * @group AppKernel
 * @group AppKernelTests
 * @group AppEvents
 * @group AppConfigUpdatedTest
 * Add your own group annotations below this line
 */
class AppConfigUpdatedTest extends Unit
{
    /**
     * @var \SprykerTest\AsyncApi\AppKernel\AppKernelAsyncApiTester
     */
    protected AppKernelAsyncApiTester $tester;

    /**
     * @return void
     */
    public function testGivenTenantIsNotConnectedWithAppWhenMarketplaceOwnerConfiguresTheAppThenTheAppConfigUpdatedMessageIsSend(): void
    {
        // Arrange
        $appIdentifier = Uuid::uuid4()->toString();
        $appConfigTransfer = $this->tester->haveAppConfigTransfer([AppConfigTransfer::IS_ACTIVE => true, AppConfigTransfer::CONFIG => ['foo' => 'bar'], AppConfigTransfer::STATUS => 'NEW']);
        $appConfigUpdatedTransfer = $this->tester->haveAppConfigUpdatedTransfer($appConfigTransfer->toArray() + [AppConfigUpdatedTransfer::APP_IDENTIFIER => $appIdentifier]);
        $this->tester->mockEnvironmentConfig(AppKernelConstants::APP_IDENTIFIER, $appIdentifier);

        // Act
        $this->tester->getFacade()->saveConfig($appConfigTransfer);

        // Assert
        $this->tester->assertMessageWasEmittedOnChannel($appConfigUpdatedTransfer, 'app-events');
    }
}
