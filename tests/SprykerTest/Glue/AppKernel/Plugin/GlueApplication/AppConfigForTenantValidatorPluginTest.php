<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\AppKernel\Plugin\GlueApplication;

use Codeception\Stub;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Glue\AppKernel\AppKernelConfig;
use Spryker\Glue\AppKernel\AppKernelDependencyProvider;
use Spryker\Glue\AppKernel\AppKernelFactory;
use Spryker\Glue\AppKernel\Plugin\GlueApplication\AppConfigForTenantValidatorPlugin;
use Spryker\Glue\Kernel\Backend\Container;
use Spryker\Zed\AppKernel\AppKernelConfig as ZedAppKernelConfig;
use SprykerTest\Glue\AppKernel\AppKernelTester;
use SprykerTest\Glue\Testify\Helper\DependencyProviderHelperTrait;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group AppKernel
 * @group Plugin
 * @group GlueApplication
 * @group AppConfigForTenantValidatorPluginTest
 * Add your own group annotations below this line
 */
class AppConfigForTenantValidatorPluginTest extends Unit
{
    use DependencyProviderHelperTrait;

    protected AppKernelTester $tester;

    public function testGivenAPathIsExceludedFromTheValidationWhenTheValidatorIsExecutedThenTheRequestValidationIsSuccessful(): void
    {
        // Arrange
        $tenantIdentifier = Uuid::uuid4()->toString();

        $this->tester->haveAppConfigForTenant($tenantIdentifier, status: ZedAppKernelConfig::APP_STATUS_DISCONNECTED);

        $configStub = Stub::make(AppKernelConfig::class, [
            'getValidationExcludedPaths' => ['/excluded-endpoint'],
        ]);

        $container = (new AppKernelDependencyProvider())->provideDependencies(new Container());

        $factoryStub = Stub::make(AppKernelFactory::class, ['getConfig' => $configStub]);
        $factoryStub->setContainer($container);

        $appConfigForTenantValidatorPlugin = new AppConfigForTenantValidatorPlugin();
        $appConfigForTenantValidatorPlugin->setFactory($factoryStub);

        $glueRequestTransfer = new GlueRequestTransfer();
        $glueRequestTransfer
            ->setMethod('POST')
            ->setPath('/excluded-endpoint')
            ->setMeta([
                AppKernelConfig::HEADER_TENANT_IDENTIFIER => $tenantIdentifier,
                'content-type' => 'application/json',
            ]);

        // Act
        $glueRequestValidationTransfer = $appConfigForTenantValidatorPlugin->validate($glueRequestTransfer);

        // Assert
        $this->assertTrue($glueRequestValidationTransfer->getIsValid());
    }

    public function testGivenAppConfigIsMarkedAsNewWhenTheValidatorIsExecutedThenTheRequestValidationIsSuccessful(): void
    {
        // Arrange
        $tenantIdentifier = Uuid::uuid4()->toString();

        $this->tester->haveAppConfigForTenant($tenantIdentifier, status: ZedAppKernelConfig::APP_STATUS_NEW);

        $appConfigForTenantValidatorPlugin = new AppConfigForTenantValidatorPlugin();

        $glueRequestTransfer = new GlueRequestTransfer();
        $glueRequestTransfer
            ->setMethod('POST')
            ->setPath('/existing-endpoint')
            ->setMeta([
                AppKernelConfig::HEADER_TENANT_IDENTIFIER => $tenantIdentifier,
                'content-type' => 'application/json',
            ]);

        // Act
        $glueRequestValidationTransfer = $appConfigForTenantValidatorPlugin->validate($glueRequestTransfer);

        // Assert
        $this->assertTrue($glueRequestValidationTransfer->getIsValid());
    }

    public function testGivenAppConfigIsMarkedAsConnectedWhenTheValidatorIsExecutedThenTheRequestValidationIsSuccessful(): void
    {
        // Arrange
        $tenantIdentifier = Uuid::uuid4()->toString();

        $this->tester->haveAppConfigForTenant($tenantIdentifier, status: ZedAppKernelConfig::APP_STATUS_CONNECTED);

        $appConfigForTenantValidatorPlugin = new AppConfigForTenantValidatorPlugin();

        $glueRequestTransfer = new GlueRequestTransfer();
        $glueRequestTransfer
            ->setMethod('POST')
            ->setPath('/existing-endpoint')
            ->setMeta([
                AppKernelConfig::HEADER_TENANT_IDENTIFIER => $tenantIdentifier,
                'content-type' => 'application/json',
            ]);

        // Act
        $glueRequestValidationTransfer = $appConfigForTenantValidatorPlugin->validate($glueRequestTransfer);

        // Assert
        $this->assertTrue($glueRequestValidationTransfer->getIsValid());
    }

    public function testGivenAppConfigIsMarkedAsDisconnectedWhenTheValidatorIsExecutedThenTheRequestValidationIsFailedAddAStatusCode403IsReturned(): void
    {
        // Arrange
        $tenantIdentifier = Uuid::uuid4()->toString();

        $this->tester->haveAppConfigForTenant($tenantIdentifier, status: ZedAppKernelConfig::APP_STATUS_DISCONNECTED);

        $appConfigForTenantValidatorPlugin = new AppConfigForTenantValidatorPlugin();

        $glueRequestTransfer = new GlueRequestTransfer();
        $glueRequestTransfer
            ->setMethod('POST')
            ->setPath('/existing-endpoint')
            ->setMeta([
                AppKernelConfig::HEADER_TENANT_IDENTIFIER => $tenantIdentifier,
                'content-type' => 'application/json',
            ]);

        // Act
        $glueRequestValidationTransfer = $appConfigForTenantValidatorPlugin->validate($glueRequestTransfer);

        // Assert
        $this->assertFalse($glueRequestValidationTransfer->getIsValid());
        $this->assertSame(Response::HTTP_FORBIDDEN, $glueRequestValidationTransfer->getStatus());
    }

    public function testGivenAppConfigIsMarkedAsDisconnectedWhenTheValidatorIsExecutedAndTheTenantIdentifierHeaderIsAnArrayThenTheRequestValidationIsFailedAddAStatusCode403IsReturned(): void
    {
        // Arrange
        $tenantIdentifier = Uuid::uuid4()->toString();

        $this->tester->haveAppConfigForTenant($tenantIdentifier, status: ZedAppKernelConfig::APP_STATUS_DISCONNECTED);

        $appConfigForTenantValidatorPlugin = new AppConfigForTenantValidatorPlugin();

        $glueRequestTransfer = new GlueRequestTransfer();
        $glueRequestTransfer
            ->setMethod('POST')
            ->setPath('/existing-endpoint')
            ->setMeta([
                AppKernelConfig::HEADER_TENANT_IDENTIFIER => [$tenantIdentifier],
                'content-type' => 'application/json',
            ]);

        // Act
        $glueRequestValidationTransfer = $appConfigForTenantValidatorPlugin->validate($glueRequestTransfer);

        // Assert
        $this->assertFalse($glueRequestValidationTransfer->getIsValid());
        $this->assertSame(Response::HTTP_FORBIDDEN, $glueRequestValidationTransfer->getStatus());
    }
}
