<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AppKernel\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\MyAppConfigTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Zed\AppKernel\Persistence\Exception\AppConfigNotFoundException;
use SprykerTest\Zed\AppKernel\AppKernelBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AppKernel
 * @group Business
 * @group Facade
 * @group AppKernelFacadeTest
 * Add your own group annotations below this line
 */
class AppKernelFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\AppKernel\AppKernelBusinessTester
     */
    protected AppKernelBusinessTester $tester;

    /**
     * @return void
     */
    public function testGetConfigReturnsAppConfigTransferWhenAppConfigByTenantIdentifierFound(): void
    {
        // Arrange
        $tenantIdentifier = Uuid::uuid4()->toString();
        $seed = [
            AppConfigTransfer::TENANT_IDENTIFIER => $tenantIdentifier,
            AppConfigTransfer::CONFIG => ['foo' => 'foo', 'bar' => 123],
        ];

        $this->tester->havePersistedAppConfigTransfer($seed);

        $appConfigCriteriaTransfer = new AppConfigCriteriaTransfer();
        $appConfigCriteriaTransfer->setTenantIdentifier($tenantIdentifier);

        // Act
        $myAppConfigTransfer = $this->tester->getFacade()->getConfig($appConfigCriteriaTransfer, new MyAppConfigTransfer());

        // Assert
        $this->assertInstanceOf(MyAppConfigTransfer::class, $myAppConfigTransfer);
        $this->assertSame('foo', $myAppConfigTransfer->getFoo());
        $this->assertSame(123, $myAppConfigTransfer->getBar());
    }

    /**
     * @return void
     */
    public function testGetConfigThrowsAnExceptionWhenAppConfigByTenantIdentifierCouldNotBeFound(): void
    {
        // Arrange
        $tenantIdentifier = Uuid::uuid4()->toString();

        $appConfigCriteriaTransfer = new AppConfigCriteriaTransfer();
        $appConfigCriteriaTransfer->setTenantIdentifier($tenantIdentifier);

        // Expect
        $this->expectException(AppConfigNotFoundException::class);

        // Act
        $this->tester->getFacade()->getConfig($appConfigCriteriaTransfer, new MyAppConfigTransfer());
    }
}
