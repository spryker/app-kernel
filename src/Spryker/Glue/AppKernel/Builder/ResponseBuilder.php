<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AppKernel\Builder;

use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class ResponseBuilder implements ResponseBuilderInterface
{
    /**
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(protected UtilEncodingServiceInterface $utilEncodingService)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestValidationTransfer $glueRequestValidationTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function buildRequestNotValidResponse(
        GlueRequestValidationTransfer $glueRequestValidationTransfer
    ): GlueResponseTransfer {
        $errors = [];

        $glueResponseTransfer = new GlueResponseTransfer();

        foreach ($glueRequestValidationTransfer->getErrors() as $error) {
            $glueResponseTransfer->addError($error);

            $errors[] = [
                'code' => $error->getCode(),
                'message' => $error->getMessage(),
                'status' => $error->getStatus(),
            ];
        }

        return $glueResponseTransfer
            ->setContent($this->utilEncodingService->encodeJson(['errors' => $errors]))
            ->setHttpStatus($glueRequestValidationTransfer->getStatus() ?? Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param string $errorMessage
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function buildErrorResponse(string $errorMessage): GlueResponseTransfer
    {
        $errorData = $this->composeErrorArray($errorMessage);

        return (new GlueResponseTransfer())
            ->setContent($this->utilEncodingService
                ->encodeJson([
                    'errors' => [
                        $errorData,
                    ],
                ]))
            ->addError((new GlueErrorTransfer())->fromArray($errorData))
            ->setHttpStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param \Generated\Shared\Transfer\AppConfigTransfer|null $appConfigTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function buildSuccessfulResponse(?AppConfigTransfer $appConfigTransfer = null): GlueResponseTransfer
    {
        $glueResponseTransfer = (new GlueResponseTransfer())->setHttpStatus(Response::HTTP_NO_CONTENT);

        if ($appConfigTransfer) {
            $content = [
                'tenantIdentifier' => $appConfigTransfer->getTenantIdentifier(),
                'configuration' => $appConfigTransfer->getConfig(),
            ];

            $glueResponseTransfer
                ->setContent($this->utilEncodingService->encodeJson($content))
                ->setHttpStatus(Response::HTTP_OK);
        }

        return $glueResponseTransfer;
    }

    /**
     * @param string $errorMessage
     *
     * @return array<string, string|int>
     */
    protected function composeErrorArray(string $errorMessage): array
    {
        return [
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'message' => $errorMessage,
            'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
        ];
    }
}
