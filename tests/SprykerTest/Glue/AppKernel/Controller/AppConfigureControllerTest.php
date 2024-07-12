<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\AppKernel\Controller;

use Codeception\Stub;
use Codeception\Test\Unit;
use Exception;
use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\AppConfigValidateResponseTransfer;
use Generated\Shared\Transfer\ConfigurationValidationErrorTransfer;
use Orm\Zed\AppKernel\Persistence\SpyAppConfigQuery;
use Ramsey\Uuid\Uuid;
use Spryker\Glue\AppKernel\AppKernelConfig;
use Spryker\Zed\AppKernel\AppKernelConfig as SprykerAppKernelConfig;
use Spryker\Zed\AppKernel\AppKernelDependencyProvider;
use Spryker\Zed\AppKernelExtension\Dependency\Plugin\AppKernelPlatformPluginInterface;
use Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationAfterSavePluginInterface;
use Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationBeforeSavePluginInterface;
use SprykerTest\Glue\AppKernel\AppKernelTester;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;
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
 * @group AppConfigureControllerTest
 * Add your own group annotations below this line
 */
class AppConfigureControllerTest extends Unit
{
    use LocatorHelperTrait;
    use DependencyProviderHelperTrait;
    use DependencyHelperTrait;
    use DataCleanupHelperTrait;

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
    public function testPostConfigureReturnsSuccessResponseAndActivatesTheAppConfigurationWhenAnAppConfigurationExistsAndWasMarkedAsDeactivated(): void
    {
        // Arrange
        $glueRequest = $this->tester->createGlueRequestFromFixture('valid-config-request');
        $this->tester->havePersistedAppConfigTransfer([AppConfigTransfer::TENANT_IDENTIFIER => 'tenant-identifier', AppConfigTransfer::IS_ACTIVE => false]);
        $appConfigController = $this->tester->createAppConfigController();

        // Act
        $glueResponse = $appConfigController->postConfigureAction($glueRequest);

        // Assert
        $this->tester->assertGlueResponseContainsSuccessContents($glueRequest, $glueResponse);
        $this->tester->assertAppConfigIsActivated('tenant-identifier');
    }

    /**
     * @return void
     */
    public function testPostConfigureReturnsSuccessResponseAndKeepsPreviousConfigurationWhenAnAppConfigurationExists(): void
    {
        // Arrange
        $glueRequest = $this->tester->createGlueRequestFromFixture('valid-config-request');
        $this->tester->havePersistedAppConfigTransfer([AppConfigTransfer::TENANT_IDENTIFIER => 'tenant-identifier', AppConfigTransfer::IS_ACTIVE => false, AppConfigTransfer::CONFIG => ['key' => 'value']]);
        $appConfigController = $this->tester->createAppConfigController();

        $expectedConfig = ['key' => 'value', 'clientId' => 'ClientID', 'clientSecret' => 'ClientSecret', 'isActive' => true];

        // Act
        $glueResponse = $appConfigController->postConfigureAction($glueRequest);

        // Set the expected content for the validation
        $glueRequest->setContent('{"data":{"type":"configuration","attributes":{"configuration":"{\"key\":\"value\",\"clientId\":\"ClientID\",\"clientSecret\":\"ClientSecret\",\"isActive\":true}"}}}');

        // Assert
        $this->tester->assertGlueResponseContainsSuccessContents($glueRequest, $glueResponse);
        $this->tester->assertPersistedAppConfig('tenant-identifier', $expectedConfig);
    }

    /**
     * @return void
     */
    public function testGivenAnAppConfigInStatusDisconnectedAndIsActiveTrueWhenTheConfigurationIsSavedTheStatusIsChangedToConnected(): void
    {
        // Arrange
        $glueRequest = $this->tester->createGlueRequestFromFixture('valid-config-request');
        $this->tester->havePersistedAppConfigTransfer([AppConfigTransfer::TENANT_IDENTIFIER => 'tenant-identifier', AppConfigTransfer::IS_ACTIVE => true, AppConfigTransfer::STATUS => SprykerAppKernelConfig::APP_STATUS_DISCONNECTED, AppConfigTransfer::CONFIG => ['key' => 'value']]);
        $appConfigController = $this->tester->createAppConfigController();

        // Act
        $appConfigController->postConfigureAction($glueRequest);

        // Assert
        $this->tester->assertAppConfigStatus('tenant-identifier', SprykerAppKernelConfig::APP_STATUS_CONNECTED);
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

        $this->getDependencyProviderHelper()->setDependency(AppKernelDependencyProvider::PLUGIN_CONFIGURATION_BEFORE_SAVE_PLUGINS, [$configurationBeforeSavePlugin]);

        // Act
        $appConfigController->postConfigureAction($glueRequest);

        // Assert
        $this->assertTrue($this->beforeSavePluginWasExecuted, 'Expected that the ConfigurationBeforeSavePluginInterface gets executed but was not.');
    }

    /**
     * @return void
     */
    public function testPostConfigureExecutesBeforeSavePluginWhenAnExistingAppConfigurationWasFoundAndIsSetToEnabledAgain(): void
    {
        // Arrange
        $glueRequest = $this->tester->createGlueRequestFromFixture('valid-config-request');
        $this->tester->havePersistedAppConfigTransfer([AppConfigTransfer::TENANT_IDENTIFIER => 'tenant-identifier', AppConfigTransfer::IS_ACTIVE => false]);
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

        $this->getDependencyProviderHelper()->setDependency(AppKernelDependencyProvider::PLUGIN_CONFIGURATION_BEFORE_SAVE_PLUGINS, [$configurationBeforeSavePlugin]);

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

        $this->getDependencyProviderHelper()->setDependency(AppKernelDependencyProvider::PLUGIN_CONFIGURATION_AFTER_SAVE_PLUGINS, [$configurationAfterSavePlugin]);

        // Act
        $appConfigController->postConfigureAction($glueRequest);

        // Assert
        $this->assertTrue($this->afterSavePluginWasExecuted, 'Expected that the ConfigurationAfterSavePluginInterface gets executed but was not.');
    }

    /**
     * @return void
     */
    public function testPostConfigureExecutesAfterSavePluginWhenAnExistingAppConfigurationWasFoundAndIsSetToEnabledAgain(): void
    {
        // Arrange
        $glueRequest = $this->tester->createGlueRequestFromFixture('valid-config-request');
        $this->tester->havePersistedAppConfigTransfer([AppConfigTransfer::TENANT_IDENTIFIER => 'tenant-identifier', AppConfigTransfer::IS_ACTIVE => false]);
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

        $this->getDependencyProviderHelper()->setDependency(AppKernelDependencyProvider::PLUGIN_CONFIGURATION_AFTER_SAVE_PLUGINS, [$configurationAfterSavePlugin]);

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

        $this->getDependencyProviderHelper()->setDependency(AppKernelDependencyProvider::PLUGIN_CONFIGURATION_BEFORE_SAVE_PLUGINS, [$configurationBeforeSavePlugin]);

        $appConfigController = $this->tester->createAppConfigController();

        // Act
        $glueResponse = $appConfigController->postConfigureAction($glueRequest);

        // Assert
        $this->tester->assertGlueResponseContainsErrorMessageWhenExceptionWasThrown($glueResponse);
    }

    public function testReceivingConfigurationFromAppStoreCatalogWithoutTheAcceptLanguageHeaderAppliesDefaultLocaleEnUSAndSavesAppConfigurationWhenPlatformValidationWasSuccessful(): void
    {
        // Arrange
        $tenantIdentifier = Uuid::uuid4()->toString();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($tenantIdentifier): void {
            SpyAppConfigQuery::create()
                ->filterByTenantIdentifier($tenantIdentifier)
                ->delete();
        });

        $appConfigValidateResponseTransfer = new AppConfigValidateResponseTransfer();
        $appConfigValidateResponseTransfer->setIsSuccessful(true);

        $platformPluginMock = Stub::makeEmpty(AppKernelPlatformPluginInterface::class, [
            'validateConfiguration' => $appConfigValidateResponseTransfer,
        ]);

        $this->getDependencyHelper()->setDependency(AppKernelDependencyProvider::PLUGIN_PLATFORM, $platformPluginMock);

        $this->tester->setHeaders([
            AppKernelConfig::HEADER_TENANT_IDENTIFIER => $tenantIdentifier,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);

        // Act
        $response = $this->tester->sendPost('/private/configure', $this->tester->getAppConfigureRequestData());

        // Assert
        $this->assertSame(200, $response->getStatusCode());
        $this->tester->assertAppConfigForTenantEquals($tenantIdentifier);
    }

    public function testReceivingConfigurationFromAppStoreCatalogSavesAppConfigurationWhenPlatformValidationWasSuccessful(): void
    {
        // Arrange
        $tenantIdentifier = Uuid::uuid4()->toString();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($tenantIdentifier): void {
            SpyAppConfigQuery::create()
                ->filterByTenantIdentifier($tenantIdentifier)
                ->delete();
        });

        $appConfigValidateResponseTransfer = new AppConfigValidateResponseTransfer();
        $appConfigValidateResponseTransfer->setIsSuccessful(true);

        $platformPluginMock = Stub::makeEmpty(AppKernelPlatformPluginInterface::class, [
            'validateConfiguration' => $appConfigValidateResponseTransfer,
        ]);

        $this->getDependencyHelper()->setDependency(AppKernelDependencyProvider::PLUGIN_PLATFORM, $platformPluginMock);

        $this->tester->setHeaders([
            AppKernelConfig::HEADER_TENANT_IDENTIFIER => $tenantIdentifier,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Accept-Language' => 'en-US, en;q=0.9,*;q=0.5',
        ]);

        // Act
        $response = $this->tester->sendPost('/private/configure', $this->tester->getAppConfigureRequestData());

        // Assert
        $this->assertSame(200, $response->getStatusCode());
        $this->tester->assertAppConfigForTenantEquals($tenantIdentifier);
    }

    public function testReceivingConfigurationFromAppStoreCatalogReturns422UnprocessableEntityWhenPlatformValidationFailed(): void
    {
        // Arrange
        $tenantIdentifier = Uuid::uuid4()->toString();

        $appConfigValidateResponseTransfer = new AppConfigValidateResponseTransfer();
        $appConfigValidateResponseTransfer->setIsSuccessful(false);

        $platformPluginMock = Stub::makeEmpty(AppKernelPlatformPluginInterface::class, [
            'validateConfiguration' => $appConfigValidateResponseTransfer,
        ]);

        $this->getDependencyHelper()->setDependency(AppKernelDependencyProvider::PLUGIN_PLATFORM, $platformPluginMock);

        $this->tester->setHeaders([
            AppKernelConfig::HEADER_TENANT_IDENTIFIER => $tenantIdentifier,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Accept-Language' => 'en-US, en;q=0.9,*;q=0.5',
        ]);

        // Act
        $response = $this->tester->sendPost('/private/configure', $this->tester->getAppConfigureRequestData());

        // Assert
        $this->assertSame(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->tester->assertAppConfigurationForTenantDoesNotExist($tenantIdentifier);
    }

    public function testReceivingConfigurationFromAppStoreCatalogReturns422UnprocessableEntityWithErrorMessagesWhenPlatformValidationFailed(): void
    {
        // Arrange
        $tenantIdentifier = Uuid::uuid4()->toString();

        $configurationValidationErrorTransfer = new ConfigurationValidationErrorTransfer();
        $configurationValidationErrorTransfer->addErrorMessage('Something went wrong');

        $appConfigValidateResponseTransfer = new AppConfigValidateResponseTransfer();
        $appConfigValidateResponseTransfer
            ->setIsSuccessful(false)
            ->addConfigurationValidationError($configurationValidationErrorTransfer);

        $platformPluginMock = Stub::makeEmpty(AppKernelPlatformPluginInterface::class, [
            'validateConfiguration' => $appConfigValidateResponseTransfer,
        ]);

        $this->getDependencyHelper()->setDependency(AppKernelDependencyProvider::PLUGIN_PLATFORM, $platformPluginMock);

        $this->tester->setHeaders([
            AppKernelConfig::HEADER_TENANT_IDENTIFIER => $tenantIdentifier,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Accept-Language' => 'en-US, en;q=0.9,*;q=0.5',
        ]);

        // Act
        $response = $this->tester->sendPost('/private/configure', $this->tester->getAppConfigureRequestData());

        // Assert
        $this->assertSame(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->tester->assertAppConfigurationForTenantDoesNotExist($tenantIdentifier);
    }

    public function testReceivingConfigurationFromAppStoreCatalogReturns422UnprocessableEntityWithErrorMessagesWhenPlatformValidationThrowsAnException(): void
    {
        // Arrange
        $tenantIdentifier = Uuid::uuid4()->toString();

        $platformPluginMock = Stub::makeEmpty(AppKernelPlatformPluginInterface::class, [
            'validateConfiguration' => static function (): never {
                throw new Exception('Something went wrong');
            },
        ]);

        $this->getDependencyHelper()->setDependency(AppKernelDependencyProvider::PLUGIN_PLATFORM, $platformPluginMock);

        $this->tester->setHeaders([
            AppKernelConfig::HEADER_TENANT_IDENTIFIER => $tenantIdentifier,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Accept-Language' => 'en-US, en;q=0.9,*;q=0.5',
        ]);

        // Act
        $response = $this->tester->sendPost('/private/configure', $this->tester->getAppConfigureRequestData());

        // Assert
        $this->assertSame(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
        $this->tester->assertAppConfigurationForTenantDoesNotExist($tenantIdentifier);
    }

    public function testDisconnectAppForAnExistingTenantDeactivatesAppConfiguration(): void
    {
        // Arrange
        $tenantIdentifier = Uuid::uuid4()->toString();

        $this->tester->haveAppConfigForTenant($tenantIdentifier);
        $this->tester->assertAppConfigForTenantEquals($tenantIdentifier);

        $appConfigValidateResponseTransfer = new AppConfigValidateResponseTransfer();
        $appConfigValidateResponseTransfer->setIsSuccessful(false);

        $platformPluginMock = Stub::makeEmpty(AppKernelPlatformPluginInterface::class, [
            'validateConfiguration' => $appConfigValidateResponseTransfer,
        ]);

        $this->getDependencyHelper()->setDependency(AppKernelDependencyProvider::PLUGIN_PLATFORM, $platformPluginMock);

        $this->tester->setHeaders([
            AppKernelConfig::HEADER_TENANT_IDENTIFIER => $tenantIdentifier,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Accept-Language' => 'en-US, en;q=0.9,*;q=0.5',
        ]);

        // Act
        $response = $this->tester->sendPost('/private/disconnect');

        // Assert
        $this->assertSame(204, $response->getStatusCode());
        $this->tester->assertAppConfigurationForTenantIsDeactivated($tenantIdentifier);
    }
}
