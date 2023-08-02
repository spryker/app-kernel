<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Glue\App\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Symfony\Component\HttpFoundation\Response;

class AppAssertionHelper extends Module
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponse
     *
     * @return void
     */
    public function assertGlueResponseContainsSuccessContents(GlueRequestTransfer $glueRequestTransfer, GlueResponseTransfer $glueResponse): void
    {
        $this->assertSame(Response::HTTP_OK, $glueResponse->getHttpStatus());

        $expectedConfiguration = json_decode(json_decode($glueRequestTransfer->getContent(), true)['data']['attributes']['configuration'], true);
        $expectedConfiguration['store_reference'] = $glueRequestTransfer->getMeta()['x-store-reference'][0];

        $actualConfiguration = json_decode($glueResponse->getContent(), true)['configuration'];

        sort($expectedConfiguration);
        sort($actualConfiguration);

        $expectedConfiguration = array_values(array_filter($expectedConfiguration));
        $actualConfiguration = array_values(array_filter($actualConfiguration));

        $this->assertEquals($expectedConfiguration, $actualConfiguration);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponse
     *
     * @return void
     */
    public function assertGlueResponseContainsErrorMessageWhenXStoreReferenceIsMissing(GlueResponseTransfer $glueResponse): void
    {
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $glueResponse->getHttpStatus());
        $contentData = json_decode($glueResponse->getContent(), true);
        $this->assertNotEmpty($contentData);
        $this->assertEquals(1, count($contentData['errors']));
        $this->assertEquals(
            'X-Store-Reference in header is required.',
            $contentData['errors'][0]['detail'],
        );
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponse
     *
     * @return void
     */
    public function assertGlueResponseContainsErrorMessageWhenRequestBodyHasInvalidStructure(GlueResponseTransfer $glueResponse): void
    {
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $glueResponse->getHttpStatus());
        $contentData = json_decode($glueResponse->getContent(), true);
        $this->assertNotEmpty($contentData);
        $this->assertEquals(1, count($contentData['errors']));
        $this->assertEquals(
            'Wrong request format.',
            $contentData['errors'][0]['detail'],
        );
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponse
     *
     * @return void
     */
    public function assertGlueResponseContainsErrorMessageWhenRequestBodyIsMissingConfigurationData(GlueResponseTransfer $glueResponse): void
    {
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $glueResponse->getHttpStatus());
        $contentData = json_decode($glueResponse->getContent(), true);
        $this->assertNotEmpty($contentData);
        $this->assertEquals(3, count($contentData['errors']));
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponse
     *
     * @return void
     */
    public function assertGlueResponseContainsErrorMessageWhenVertexConfigurationDataIsInvalid(GlueResponseTransfer $glueResponse): void
    {
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $glueResponse->getHttpStatus());
        $contentData = json_decode($glueResponse->getContent(), true);
        $this->assertNotEmpty($contentData);
        $this->assertEquals(5, count($contentData['errors']));
        $this->assertEquals('Client ID is required.', $contentData['errors'][0]['detail']);
        $this->assertEquals('Client ID must be a string.', $contentData['errors'][1]['detail']);
        $this->assertEquals('Client Secret is required.', $contentData['errors'][2]['detail']);
        $this->assertEquals('Client Secret must be a string.', $contentData['errors'][3]['detail']);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponse
     *
     * @return void
     */
    public function assertGlueResponseContainsErrorMessageWhenVertexCredentialIsNotValid(GlueResponseTransfer $glueResponse): void
    {
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $glueResponse->getHttpStatus());
        $contentData = json_decode($glueResponse->getContent(), true);
        $this->assertNotEmpty($contentData);
        $this->assertEquals(1, count($contentData['errors']));
        $this->assertEquals('Invalid credentials', $contentData['errors'][0]['detail']);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponse
     *
     * @return void
     */
    public function assertGlueResponseContainsSuccessContentsWhenDisconnectIsSuccessful(GlueResponseTransfer $glueResponse): void
    {
        $this->assertEquals(Response::HTTP_NO_CONTENT, $glueResponse->getHttpStatus());
        $this->assertEquals(0, $glueResponse->getErrors()->count());
    }
}
