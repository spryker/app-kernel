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
use Spryker\Glue\AppKernel\Dependency\Service\AppKernelToUtilEncodingServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class ResponseBuilder implements ResponseBuilderInterface
{
    public function __construct(protected AppKernelToUtilEncodingServiceInterface $appKernelToUtilEncodingService)
    {
    }

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
                'confirm' => $error->getConfirm()?->toArray(true, true),
            ];
        }

        return $glueResponseTransfer
            ->setContent($this->appKernelToUtilEncodingService->encodeJson(['errors' => $errors]))
            ->setHttpStatus($glueRequestValidationTransfer->getStatus() ?? Response::HTTP_BAD_REQUEST);
    }

    public function buildErrorResponse(string $errorMessage): GlueResponseTransfer
    {
        $errorData = $this->composeErrorArray($errorMessage);

        return (new GlueResponseTransfer())
            ->setContent($this->appKernelToUtilEncodingService
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
     */
    public function buildSuccessfulResponse(?AppConfigTransfer $appConfigTransfer = null): GlueResponseTransfer
    {
        $glueResponseTransfer = (new GlueResponseTransfer())->setHttpStatus(Response::HTTP_NO_CONTENT);

        if ($appConfigTransfer instanceof AppConfigTransfer) {
            $content = [
                'tenantIdentifier' => $appConfigTransfer->getTenantIdentifier(),
                'configuration' => $appConfigTransfer->getConfig(),
            ];

            $glueResponseTransfer
                ->setContent($this->appKernelToUtilEncodingService->encodeJson($content))
                ->setHttpStatus(Response::HTTP_OK);
        }

        return $glueResponseTransfer;
    }

    /**
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
