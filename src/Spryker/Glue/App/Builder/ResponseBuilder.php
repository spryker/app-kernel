<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\App\Builder;

use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class ResponseBuilder implements ResponseBuilderInterface
{
    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected UtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(UtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
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

        foreach ($glueRequestValidationTransfer->getErrors() as $error) {
            $errors[] = [
                'code' => $error->getCode(),
                'detail' => $error->getMessage(),
                'status' => $error->getStatus(),
            ];
        }

        return (new GlueResponseTransfer())
            ->setContent($this->utilEncodingService->encodeJson(['errors' => $errors]))
            ->setHttpStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param string $errorMessage
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function buildErrorResponse(string $errorMessage): GlueResponseTransfer
    {
        return (new GlueResponseTransfer())
            ->setContent($this->utilEncodingService
                ->encodeJson([
                    'errors' => [
                        $this->composeErrorArray($errorMessage),
                    ],
                ]))
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
                'storeReference' => $appConfigTransfer->getStoreReference(),
                'configuration' => $appConfigTransfer->modifiedToArray(false),
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
     * @return array
     */
    protected function composeErrorArray(string $errorMessage): array
    {
        return [
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'detail' => $errorMessage,
            'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
        ];
    }
}
