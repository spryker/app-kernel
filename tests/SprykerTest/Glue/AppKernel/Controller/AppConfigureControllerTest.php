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
use Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationAfterSavePluginInterface;
use Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationBeforeSavePluginInterface;
use SprykerTest\Glue\AppKernel\AppKernelTester;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Zed\Testify\Helper\Business\DependencyProviderHelperTrait;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group AppKernel
 * @group Controller
 * @group AppConfigureControllerTest
 * Add your own group annotations below this line
 */
class AppConfigureControllerTest extends Unit
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
    public bool $beforeSavePluginWasExecuted = false;

    /**
     * @var bool
     */
    public bool $afterSavePluginWasExecuted = false;

    /**
     * @return void
     */
    public function testPostConfigureReturnsSuccessResponseWhenRequestIsValid(): void
    {
        // Arrange
        $glueRequest = $this->tester->createGlueRequestFromFixture('valid-config-request');
        $appConfigController = $this->tester->createAppConfigController();

        // Act
        $glueResponse = $appConfigController->postConfigureAction($glueRequest);

        // Assert
        $this->tester->assertGlueResponseContainsSuccessContents($glueRequest, $glueResponse);
        $this->tester->assertAppConfigIsPersisted('tenant-identifier');
    }

    /**
     * @return void
     */
    public function testPostConfigureExecutesBeforeSavePlugin(): void
    {
        // Arrange
        $glueRequest = $this->tester->createGlueRequestFromFixture('valid-config-request');
        $appConfigController = $this->tester->createAppConfigController();

        $configurationBeforeSavePlugin = new class ($this) implements ConfigurationBeforeSavePluginInterface {
            /**
             * @param \SprykerTest\Glue\AppKernel\Controller\AppConfigureControllerTest $test
             */
            public function __construct(protected AppConfigureControllerTest $test)
            {
            }

            /**
             * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
             *
             * @return \Generated\Shared\Transfer\AppConfigTransfer
             */
            public function beforeSave(AppConfigTransfer $appConfigTransfer): AppConfigTransfer
            {
                $this->test->beforeSavePluginWasExecuted = true;

                return $appConfigTransfer;
            }
        };

        $this->getDependencyProviderHelper()->setDependency(AppKernelDependencyProvider::PLUGIN_CONFIGURATION_BEFORE_SAVE_PLUGIN, $configurationBeforeSavePlugin);

        // Act
        $appConfigController->postConfigureAction($glueRequest);

        // Assert
        $this->assertTrue($this->beforeSavePluginWasExecuted, 'Expected that the ConfigurationBeforeSavePluginInterface gets executed but was not.');
    }

    /**
     * @return void
     */
    public function testPostConfigureExecutesAfterSavePlugin(): void
    {
        // Arrange
        $glueRequest = $this->tester->createGlueRequestFromFixture('valid-config-request');
        $appConfigController = $this->tester->createAppConfigController();

        $configurationAfterSavePlugin = new class ($this) implements ConfigurationAfterSavePluginInterface {
            /**
             * @param \SprykerTest\Glue\AppKernel\Controller\AppConfigureControllerTest $test
             */
            public function __construct(protected AppConfigureControllerTest $test)
            {
            }

            /**
             * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
             *
             * @return \Generated\Shared\Transfer\AppConfigTransfer
             */
            public function afterSave(AppConfigTransfer $appConfigTransfer): AppConfigTransfer
            {
                $this->test->afterSavePluginWasExecuted = true;

                return $appConfigTransfer;
            }
        };

        $this->getDependencyProviderHelper()->setDependency(AppKernelDependencyProvider::PLUGIN_CONFIGURATION_AFTER_SAVE_PLUGIN, $configurationAfterSavePlugin);

        // Act
        $appConfigController->postConfigureAction($glueRequest);

        // Assert
        $this->assertTrue($this->afterSavePluginWasExecuted, 'Expected that the ConfigurationAfterSavePluginInterface gets executed but was not.');
    }

    /**
     * @return void
     */
    public function testPostConfigureReturnsSuccessResponseWhenRequestIsCorrectAndConfigAlreadyExistsInDatabase(): void
    {
        // Arrange
        $glueRequest = $this->tester->createGlueRequestFromFixture('valid-config-request');
        $this->tester->havePersistedAppConfigTransfer(
            ['tenantIdentifier' => 'tenant-identifier'],
        );
        $appConfigController = $this->tester->createAppConfigController();

        // Act
        $glueResponse = $appConfigController->postConfigureAction($glueRequest);

        // Assert
        $this->tester->assertGlueResponseContainsSuccessContents($glueRequest, $glueResponse);
        $this->tester->assertAppConfigIsPersisted('tenant-identifier');
    }

    /**
     * @return void
     */
    public function testPostConfigureReturnsErrorResponseWhenRequestHeaderIsMissing(): void
    {
        // Arrange
        $glueRequest = $this->tester->createGlueRequestFromFixture('invalid-config-request-without-x-tenant-identifier');
        $appConfigController = $this->tester->createAppConfigController();

        // Act
        $glueResponse = $appConfigController->postConfigureAction($glueRequest);

        // Assert
        $this->tester->assertGlueResponseContainsErrorMessageWhenXTenantIdentifierIsMissing($glueResponse);
        $this->tester->assertAppConfigIsNotPersisted('tenant-identifier');
    }

    /**
     * @return void
     */
    public function testPostConfigureReturnsErrorResponseWhenRequestBodyHasInvalidStructure(): void
    {
        // Arrange
        $glueRequest = $this->tester->createGlueRequestFromFixture('invalid-config-request-with-invalid-payload-structure');
        $appConfigController = $this->tester->createAppConfigController();

        // Act
        $glueResponse = $appConfigController->postConfigureAction($glueRequest);

        // Assert
        $this->tester->assertGlueResponseContainsErrorMessageWhenRequestBodyHasInvalidStructure($glueResponse);
        $this->tester->assertAppConfigIsNotPersisted('tenant-identifier');
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    public function testPostConfigureReturnsErrorResponseWhenAnExceptionOccurred(): void
    {
        // Arrange
        $glueRequest = $this->tester->createGlueRequestFromFixture('valid-config-request');
        $this->tester->havePersistedAppConfigTransfer(
            ['tenantIdentifier' => 'tenant-identifier'],
        );

        $configurationBeforeSavePlugin = new class implements ConfigurationBeforeSavePluginInterface {
            /**
             * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
             *
             * @throws \Exception
             *
             * @return \Generated\Shared\Transfer\AppConfigTransfer
             */
            public function beforeSave(AppConfigTransfer $appConfigTransfer): AppConfigTransfer
            {
                throw new Exception('Something went wrong');
            }
        };

        $this->getDependencyProviderHelper()->setDependency(AppKernelDependencyProvider::PLUGIN_CONFIGURATION_BEFORE_SAVE_PLUGIN, $configurationBeforeSavePlugin);

        $appConfigController = $this->tester->createAppConfigController();

        // Act
        $glueResponse = $appConfigController->postConfigureAction($glueRequest);

        // Assert
        $this->tester->assertGlueResponseContainsErrorMessageWhenExceptionWasThrown($glueResponse);
    }
}
