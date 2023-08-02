<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Glue\App\Controller;

use Codeception\Stub;
use Codeception\Test\Unit;
use SprykerTest\Glue\App\AppTester;
use Spryker\Zed\MessageBroker\Business\MessageBrokerFacadeInterface;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group App
 * @group Controller
 * @group AppConfigureControllerTest
 * Add your own group annotations below this line
 */
class AppConfigureControllerTest extends Unit
{
    use LocatorHelperTrait;

    /**
     * @var \SprykerTest\Glue\App\AppTester
     */
    protected AppTester $tester;

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
        $this->tester->assertAppConfigIsPersisted('store-reference');
    }

    /**
     * @return void
     */
    public function testPostConfigureReturnsSuccessResponseWhenRequestIsCorrectAndConfigAlreadyExistsInDatabase(): void
    {
        // Arrange
        $glueRequest = $this->tester->createGlueRequestFromFixture('valid-config-request');
        $this->tester->havePersistedAppConfigTransfer(
            ['storeReference' => 'store-reference'],
        );
        $appConfigController = $this->tester->createAppConfigController();

        // Act
        $glueResponse = $appConfigController->postConfigureAction($glueRequest);

        // Assert
        $this->tester->assertGlueResponseContainsSuccessContents($glueRequest, $glueResponse);
        $this->tester->assertAppConfigIsPersisted('store-reference');
    }

    /**
     * @return void
     */
    public function testPostConfigureReturnsErrorResponseWhenRequestHeaderIsMissing(): void
    {
        // Arrange
        $glueRequest = $this->tester->createGlueRequestFromFixture('invalid-config-request-without-x-store-reference');
        $appConfigController = $this->tester->createAppConfigController();

        // Act
        $glueResponse = $appConfigController->postConfigureAction($glueRequest);

        // Assert
        $this->tester->assertGlueResponseContainsErrorMessageWhenXStoreReferenceIsMissing($glueResponse);
        $this->tester->assertAppConfigIsNotPersisted('store-reference');
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
        $this->tester->assertAppConfigIsNotPersisted('store-reference');
    }

    /**
     * @return void
     */
    public function testPostConfigureReturnsErrorResponseWhenRequestBodyConfigurationDataIsMissing(): void
    {
        // Arrange
        $glueRequest = $this->tester->createGlueRequestFromFixture('invalid-config-request-with-missing-configuration');
        $appConfigController = $this->tester->createAppConfigController();

        // Act
        $glueResponse = $appConfigController->postConfigureAction($glueRequest);

        // Assert
        $this->tester->assertGlueResponseContainsErrorMessageWhenRequestBodyIsMissingConfigurationData($glueResponse);
        $this->tester->assertAppConfigIsNotPersisted('store-reference');
    }

    /**
     * @return void
     */
    public function testPostConfigureReturnsErrorResponseWhenAppConfigurationDataIsInvalid(): void
    {
        // Arrange
        $glueRequest = $this->tester->createGlueRequestFromFixture('invalid-config-request-with-invalid-configuration');
        $appConfigController = $this->tester->createAppConfigController();

        // Act
        $glueResponse = $appConfigController->postConfigureAction($glueRequest);

        // Assert
        $this->tester->assertGlueResponseContainsErrorMessageWhenVertexConfigurationDataIsInvalid($glueResponse);
        $this->tester->assertAppConfigIsNotPersisted('store-reference');
    }
}
