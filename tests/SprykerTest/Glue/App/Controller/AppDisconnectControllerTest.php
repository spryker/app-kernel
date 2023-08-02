<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Glue\App\Controller;

use Codeception\Test\Unit;
use SprykerTest\Glue\App\AppTester;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group App
 * @group Controller
 * @group AppDisconnectControllerTest
 * Add your own group annotations below this line
 */
class AppDisconnectControllerTest extends Unit
{
    use LocatorHelperTrait;

    /**
     * @var \SprykerTest\Glue\App\AppTester
     */
    protected AppTester $tester;

    /**
     * @return void
     */
    public function testPostDisconnectReturnsSuccessResponseWhenAppSuccessfullyDisconnected(): void
    {
        // Arrange
        $glueRequest = $this->tester->createGlueRequestFromFixture('valid-disconnect-request');
        $appConfigTransfer = $this->tester->havePersistedAppConfigTransfer(
            ['storeReference' => 'store-reference'],
        );
        $appDisconnectController = $this->tester->createAppDisconnectController();

        // Act
        $glueResponse = $appDisconnectController->postDisconnectAction($glueRequest);

        // Assert
        $this->tester->assertGlueResponseContainsSuccessContentsWhenDisconnectIsSuccessful($glueResponse);
        $this->tester->assertAppConfigIsNotPersisted($appConfigTransfer->getStoreReference());
    }

    /**
     * @return void
     */
    public function testPostDisconnectRequestReturnsInvalidResponseWhenXStoreReferenceIsMissing(): void
    {
        // Arrange
        $glueRequest = $this->tester->createGlueRequestFromFixture('invalid-disconnect-request-without-x-store-reference');
        $appConfigTransfer = $this->tester->havePersistedAppConfigTransfer();
        $appDisconnectController = $this->tester->createAppDisconnectController();

        // Act
        $glueResponse = $appDisconnectController->postDisconnectAction($glueRequest);

        // Assert
        $this->tester->assertGlueResponseContainsErrorMessageWhenXStoreReferenceIsMissing($glueResponse);
        $this->tester->assertAppConfigIsPersisted($appConfigTransfer->getStoreReference());
    }

    /**
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
}
