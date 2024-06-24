<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\AsyncApi\AppKernel\AppKernelTests\AppEvents;

use Codeception\Test\Unit;
use Spryker\Zed\AppKernel\Business\AppKernelFacade;

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
     * @var \PyzTest\AsyncApi\AppMerchant\AppMerchantAsyncApiTester
     */
    protected AppKernelAsyncApiTester $tester;

    /**
     * @return void
     */
    public function testGivenTenantIsNotConnectedWithAppWhenMarketplaceOwnerConfiguresTheAppThenTheReadyForMerchantAppOnboardingMessageIsSend(): void
    {
        // Arrange
        $readyForMerchantAppOnboardingTransfer = $this->tester->haveReadyForMerchantAppOnboardingTransfer();
        $appConfigTransfer = $this->tester->haveAppConfigTransfer();

        $onboardingTransfer = new OnboardingTransfer();
        $onboardingTransfer
            ->setStrategy('test-strategy')
            ->setUrl('url');

        $merchantAppOnboardingDetailsTransfer = new MerchantAppOnboardingDetailsTransfer();
        $merchantAppOnboardingDetailsTransfer
            ->setType('TestType')
            ->setAppName('AppName')
            ->setAppIdentifier('AppIdentifier')
            ->setOnboarding($onboardingTransfer);

        $this->tester->mockPlatformPluginImplementation($merchantAppOnboardingDetailsTransfer, new MerchantAppOnboardingResponseTransfer());
        $this->tester->setDependency(AppKernelDependencyProvider::PLUGIN_CONFIGURATION_BEFORE_SAVE_PLUGINS, [
            new InformTenantAboutMerchantAppOnboardingReadinessConfigurationBeforeSavePlugin(),
        ]);
        $this->tester->setDependency(AppKernelDependencyProvider::PLUGIN_CONFIGURATION_AFTER_SAVE_PLUGINS, []);

        // Act
        $appKernelFacade = new AppKernelFacade();
        $appKernelFacade->saveConfig($appConfigTransfer);

        // Assert
        $this->tester->assertMessageWasEmittedOnChannel($readyForMerchantAppOnboardingTransfer, 'merchant-app-events');
    }

    /**
     * @return void
     */
    public function testGivenTenantIsConnectedWithAppAndTheOnboardingDetailsWereAlreadySentWhenMarketplaceOwnerUpdatesTheAppConfigurationThenTheReadyForMerchantAppOnboardingMessageIsNotSend(): void
    {
        // Arrange
        $appConfigTransfer = $this->tester->haveAppConfigTransfer(['config' => ['tenant-onboarding-status' => 'onboarded']]);

        $onboardingTransfer = new OnboardingTransfer();
        $onboardingTransfer
            ->setStrategy('test-strategy')
            ->setUrl('url');

        $merchantAppOnboardingDetailsTransfer = new MerchantAppOnboardingDetailsTransfer();
        $merchantAppOnboardingDetailsTransfer
            ->setType('TestType')
            ->setAppName('AppName')
            ->setAppIdentifier('AppIdentifier')
            ->setOnboarding($onboardingTransfer);

        $this->tester->mockPlatformPluginImplementation($merchantAppOnboardingDetailsTransfer, new MerchantAppOnboardingResponseTransfer());
        $this->tester->setDependency(AppKernelDependencyProvider::PLUGIN_CONFIGURATION_BEFORE_SAVE_PLUGINS, [
            new InformTenantAboutMerchantAppOnboardingReadinessConfigurationBeforeSavePlugin(),
        ]);
        $this->tester->setDependency(AppKernelDependencyProvider::PLUGIN_CONFIGURATION_AFTER_SAVE_PLUGINS, []);

        // Act

        $appKernelFacade = new AppKernelFacade();
        $appKernelFacade->saveConfig($appConfigTransfer);

        // Assert
        $this->tester->assertMessageWasNotSent(ReadyForMerchantAppOnboardingTransfer::class);
    }

    /**
     * @return void
     */
    public function testGivenTenantIsNotConnectedWithAppWhenMarketplaceOwnerConfiguresTheAppThenTheReadyForMerchantAppOnboardingMessageIsSendWithTheStrategyAndUrlProvidedByTheAppPluginImplementation(): void
    {
        // Arrange
        $expectedStrategy = 'test-strategy';
        $expectedUrl = 'www.test-url.com';

        $readyForMerchantAppOnboardingTransfer = $this->tester->haveReadyForMerchantAppOnboardingTransfer();
        $appConfigTransfer = $this->tester->haveAppConfigTransfer();

        $merchantAppOnboardingDetailsTransfer = new MerchantAppOnboardingDetailsTransfer();
        $merchantAppOnboardingDetailsTransfer
            ->setType('TestType')
            ->setAppName('AppName')
            ->setAppIdentifier('AppIdentifier');

        $onboardingTransfer = new OnboardingTransfer();
        $onboardingTransfer->setStrategy($expectedStrategy);
        $onboardingTransfer->setUrl($expectedUrl);

        $merchantAppOnboardingDetailsTransfer->setOnboarding($onboardingTransfer);

        $this->tester->mockPlatformPluginImplementation($merchantAppOnboardingDetailsTransfer);

        $this->tester->setDependency(AppKernelDependencyProvider::PLUGIN_CONFIGURATION_BEFORE_SAVE_PLUGINS, [
            new InformTenantAboutMerchantAppOnboardingReadinessConfigurationBeforeSavePlugin(),
        ]);
        $this->tester->setDependency(AppKernelDependencyProvider::PLUGIN_CONFIGURATION_AFTER_SAVE_PLUGINS, []);

        // Act
        $appKernelFacade = new AppKernelFacade();
        $appKernelFacade->saveConfig($appConfigTransfer);

        // Assert
        $this->tester->assertMessageWasEmittedOnChannel($readyForMerchantAppOnboardingTransfer, 'merchant-app-events');
    }

    /**
     * @return void
     */
    public function testGivenTenantIsNotConnectedWithAppWhenThePlatformPluginImplementationReturnsTheApiStrategyAndNoUrlThenTheApiEndpointUrlIsUsedFromTheAppMerchant(): void
    {
        // Arrange
        $expectedStrategy = 'api';

        $readyForMerchantAppOnboardingTransfer = $this->tester->haveReadyForMerchantAppOnboardingTransfer();
        $appConfigTransfer = $this->tester->haveAppConfigTransfer();

        $merchantAppOnboardingDetailsTransfer = new MerchantAppOnboardingDetailsTransfer();
        $merchantAppOnboardingDetailsTransfer
            ->setType('TestType')
            ->setAppName('AppName')
            ->setAppIdentifier('AppIdentifier');

        $onboardingTransfer = new OnboardingTransfer();
        $onboardingTransfer->setStrategy($expectedStrategy);

        $merchantAppOnboardingDetailsTransfer->setOnboarding($onboardingTransfer);

        $this->tester->mockPlatformPluginImplementation($merchantAppOnboardingDetailsTransfer);

        $this->tester->setDependency(AppKernelDependencyProvider::PLUGIN_CONFIGURATION_BEFORE_SAVE_PLUGINS, [
            new InformTenantAboutMerchantAppOnboardingReadinessConfigurationBeforeSavePlugin(),
        ]);
        $this->tester->setDependency(AppKernelDependencyProvider::PLUGIN_CONFIGURATION_AFTER_SAVE_PLUGINS, []);

        // Act
        $appKernelFacade = new AppKernelFacade();
        $appKernelFacade->saveConfig($appConfigTransfer);

        // Assert
        $this->tester->assertMessageWasEmittedOnChannel($readyForMerchantAppOnboardingTransfer, 'merchant-app-events');
    }
}
