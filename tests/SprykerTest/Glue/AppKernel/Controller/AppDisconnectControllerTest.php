<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\AppKernel\Controller;

use Codeception\Test\Unit;
use Exception;
use Generated\Shared\Transfer\AppConfigTransfer;
use Spryker\Zed\AppKernel\AppKernelDependencyProvider;
use Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationAfterDeletePluginInterface;
use Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationBeforeDeletePluginInterface;
use SprykerTest\Glue\AppKernel\AppKernelTester;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Zed\Testify\Helper\Business\DependencyProviderHelperTrait;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group AppKernel
 * @group Controller
 * @group AppDisconnectControllerTest
 * Add your own group annotations below this line
 */
class AppDisconnectControllerTest extends Unit
{
    use LocatorHelperTrait;
    use DependencyProviderHelperTrait;

    /**
     * @var \SprykerTest\Glue\AppKernel\AppKernelTester
     */
    protected AppKernelTester $tester;

    /**
     * @var bool
     */
    public bool $beforeDeletePluginWasExecuted = false;

    /**
     * @var bool
     */
    public bool $afterDeletePluginWasExecuted = false;

    /**
     * @return void
     */
    public function testPostDisconnectReturnsSuccessResponseWhenAppSuccessfullyDisconnected(): void
    {
        // Arrange
        $glueRequest = $this->tester->createGlueRequestFromFixture('valid-disconnect-request');
        $appConfigTransfer = $this->tester->havePersistedAppConfigTransfer(
            ['tenantIdentifier' => 'tenant-identifier'],
        );
        $appDisconnectController = $this->tester->createAppDisconnectController();

        // Act
        $glueResponse = $appDisconnectController->postDisconnectAction($glueRequest);

        // Assert
        $this->tester->assertGlueResponseContainsSuccessContentsWhenDisconnectIsSuccessful($glueResponse);
        $this->tester->assertAppConfigIsDeactivated($appConfigTransfer->getTenantIdentifier());
    }

    /**
     * @return void
     */
    public function testPostDisconnectExecutesBeforeDeletePlugin(): void
    {
        // Arrange
        $glueRequest = $this->tester->createGlueRequestFromFixture('valid-disconnect-request');
        $this->tester->havePersistedAppConfigTransfer(
            ['tenantIdentifier' => 'tenant-identifier'],
        );

        $configurationBeforeDeletePlugin = new class ($this) implements ConfigurationBeforeDeletePluginInterface {
            /**
             * @param \SprykerTest\Glue\AppKernel\Controller\AppDisconnectControllerTest $test
             */
            public function __construct(protected AppDisconnectControllerTest $test)
            {
            }

            /**
             * @param \Generated\Shared\Transfer\AppConfigTransfer $appDisconnectTransfer
             *
             * @return \Generated\Shared\Transfer\AppConfigTransfer
             */
            public function beforeDelete(AppConfigTransfer $appDisconnectTransfer): AppConfigTransfer
            {
                $this->test->beforeDeletePluginWasExecuted = true;

                return $appDisconnectTransfer;
            }
        };

        $this->getDependencyProviderHelper()->setDependency(AppKernelDependencyProvider::PLUGIN_CONFIGURATION_BEFORE_DELETE_PLUGINS, [$configurationBeforeDeletePlugin]);

        $appDisconnectController = $this->tester->createAppDisconnectController();

        // Act
        $appDisconnectController->postDisconnectAction($glueRequest);

        // Assert
        $this->assertTrue($this->beforeDeletePluginWasExecuted, 'Expected that the ConfigurationBeforeDeletePluginInterface gets executed but was not.');
    }

    /**
     * @return void
     */
    public function testPostDisconnectExecutesAfterDeletePlugin(): void
    {
        // Arrange
        $glueRequest = $this->tester->createGlueRequestFromFixture('valid-disconnect-request');
        $this->tester->havePersistedAppConfigTransfer(
            ['tenantIdentifier' => 'tenant-identifier'],
        );

        $configurationAfterDeletePlugin = new class ($this) implements ConfigurationAfterDeletePluginInterface {
            /**
             * @param \SprykerTest\Glue\AppKernel\Controller\AppDisconnectControllerTest $test
             */
            public function __construct(protected AppDisconnectControllerTest $test)
            {
            }

            /**
             * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
             *
             * @return \Generated\Shared\Transfer\AppConfigTransfer
             */
            public function afterDelete(AppConfigTransfer $appConfigTransfer): AppConfigTransfer
            {
                $this->test->afterDeletePluginWasExecuted = true;

                return $appDisconnectTransfer;
            }
        };

        $this->getDependencyProviderHelper()->setDependency(AppKernelDependencyProvider::PLUGIN_CONFIGURATION_AFTER_DELETE_PLUGINS, [$configurationAfterDeletePlugin]);

        $appDisconnectController = $this->tester->createAppDisconnectController();

        // Act
        $appDisconnectController->postDisconnectAction($glueRequest);

        // Assert
        $this->assertTrue($this->afterDeletePluginWasExecuted, 'Expected that the ConfigurationAfterDeletePluginInterface gets executed but was not.');
    }

    /**
     * @return void
     */
    public function testPostDisconnectRequestReturnsInvalidResponseWhenXTenantIdentifierIsMissing(): void
    {
        // Arrange
        $glueRequest = $this->tester->createGlueRequestFromFixture('invalid-disconnect-request-without-x-tenant-identifier');
        $appConfigTransfer = $this->tester->havePersistedAppConfigTransfer([AppConfigTransfer::TENANT_IDENTIFIER => '123-123-abc-abc']);
        $appDisconnectController = $this->tester->createAppDisconnectController();

        // Act
        $glueResponse = $appDisconnectController->postDisconnectAction($glueRequest);

        // Assert
        $this->tester->assertGlueResponseContainsErrorMessageWhenXTenantIdentifierIsMissing($glueResponse);
        $this->tester->assertAppConfigIsPersisted($appConfigTransfer->getTenantIdentifier());
    }

    /**
     * This should fail because there is no configuration for the tenant identifier.
     *
     * @return void
     */
    public function testPostDisconnectReturnsErrorResponseWithStatusCode400WhenAppCouldNotBeDisconnected(): void
    {
        // Arrange
        $glueRequest = $this->tester->createGlueRequestFromFixture('valid-disconnect-request');

        $appDisconnectController = $this->tester->createAppDisconnectController();

        // Act
        $glueResponse = $appDisconnectController->postDisconnectAction($glueRequest);

        // Assert
        $this->assertSame(Response::HTTP_BAD_REQUEST, $glueResponse->getHttpStatus());
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    public function testPostDisconnectReturnsErrorResponseWhenAnExceptionOccurred(): void
    {
        // Arrange
        $glueRequest = $this->tester->createGlueRequestFromFixture('valid-disconnect-request');
        $this->tester->havePersistedAppConfigTransfer(
            ['tenantIdentifier' => 'tenant-identifier'],
        );

        $configurationBeforeDeletePlugin = new class implements ConfigurationBeforeDeletePluginInterface {
            /**
             * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
             *
             * @throws \Exception
             *
             * @return \Generated\Shared\Transfer\AppConfigTransfer
             */
            public function beforeDelete(AppConfigTransfer $appConfigTransfer): AppConfigTransfer
            {
                throw new Exception('Something went wrong.');
            }
        };

        $this->getDependencyProviderHelper()->setDependency(AppKernelDependencyProvider::PLUGIN_CONFIGURATION_BEFORE_DELETE_PLUGINS, [$configurationBeforeDeletePlugin]);

        $appDisconnectController = $this->tester->createAppDisconnectController();

        // Act
        $glueResponse = $appDisconnectController->postDisconnectAction($glueRequest);

        // Assert
        $this->assertSame(Response::HTTP_BAD_REQUEST, $glueResponse->getHttpStatus());
    }
}
