<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\AppKernel\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Symfony\Component\HttpFoundation\Response;

class AppKernelAssertionHelper extends Module
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
        $requestData = json_decode($glueRequestTransfer->getContent(), true);

        $expectedConfiguration = json_decode($requestData['data']['attributes']['configuration'], true);

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
    public function assertGlueResponseContainsErrorMessageWhenXTenantIdentifierIsMissing(GlueResponseTransfer $glueResponse): void
    {
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $glueResponse->getHttpStatus());
        $contentData = json_decode($glueResponse->getContent(), true);
        $this->assertNotEmpty($contentData);
        $this->assertEquals(1, count($contentData['errors']));
        $this->assertEquals(
            'X-Tenant-Identifier in header is required.',
            $contentData['errors'][0]['message'],
        );
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponse
     *
     * @return void
     */
    public function assertGlueResponseContainsErrorMessageWhenRequestBodyHasInvalidStructure(GlueResponseTransfer $glueResponse): void
    {
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $glueResponse->getHttpStatus());
        $contentData = json_decode($glueResponse->getContent(), true);

        $this->assertNotEmpty($contentData);
        $this->assertEquals(1, count($contentData['errors']));
        $this->assertEquals(
            'Wrong request format.',
            $contentData['errors'][0]['message'],
        );
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponse
     *
     * @return void
     */
    public function assertGlueResponseContainsErrorMessageWhenExceptionWasThrown(GlueResponseTransfer $glueResponse): void
    {
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $glueResponse->getHttpStatus());
        $contentData = json_decode($glueResponse->getContent(), true);
        $this->assertNotEmpty($contentData);
        $this->assertEquals(1, count($contentData['errors']));
        $this->assertEquals(
            'Tenant registration failed: Something went wrong',
            $contentData['errors'][0]['message'],
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
        $this->assertEquals('Client ID is required.', $contentData['errors'][0]['message']);
        $this->assertEquals('Client ID must be a string.', $contentData['errors'][1]['message']);
        $this->assertEquals('Client Secret is required.', $contentData['errors'][2]['message']);
        $this->assertEquals('Client Secret must be a string.', $contentData['errors'][3]['message']);
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
        $this->assertEquals('Invalid credentials', $contentData['errors'][0]['message']);
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
