<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AppKernel\Validator;

use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use GuzzleHttp\Psr7\Request;
use League\OpenAPIValidation\PSR7\Exception\Validation\InvalidBody;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use Spryker\Glue\AppKernel\AppKernelConfig;
use Spryker\Shared\Log\LoggerTrait;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class OpenApiRequestSchemaValidator
{
    use LoggerTrait;

    public function __construct(protected AppKernelConfig $appKernelConfig)
    {
    }

    public function validate(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer
    {
        $glueRequestValidationTransfer = new GlueRequestValidationTransfer();
        $glueRequestValidationTransfer->setIsValid(true);

        if ($this->isPathExcludedFromValidation($glueRequestTransfer)) {
            return $glueRequestValidationTransfer;
        }

        $openApiSchemaPath = $this->appKernelConfig->getOpenApiSchemaPath();

        if ($openApiSchemaPath === null || $openApiSchemaPath === '' || $openApiSchemaPath === '0') {
            return $glueRequestValidationTransfer;
        }

        // Converting the HTTP request to PSR7 request
        $psr7Request = new Request(
            $glueRequestTransfer->getMethod() ?? '',
            $glueRequestTransfer->getPath() ?? '',
            $glueRequestTransfer->getMeta(),
            $glueRequestTransfer->getContent(),
        );

        // Validate the request
        $validator = (new ValidatorBuilder())
            ->fromYamlFile($openApiSchemaPath)
            ->getRequestValidator();

        try {
            $validator->validate($psr7Request);
        } catch (Throwable $throwable) {
            $this->getLogger()->error(
                $this->getMessageFromThrowable($throwable),
                $glueRequestTransfer->getMeta(),
            );

            $glueErrorTransfer = new GlueErrorTransfer();
            $glueErrorTransfer
                ->setMessage($this->getMessageFromThrowable($throwable));

            $glueRequestValidationTransfer
                ->setIsValid(false)
                ->addError($glueErrorTransfer)
                ->setStatus(Response::HTTP_BAD_REQUEST);

            return $glueRequestValidationTransfer;
        }

        return $glueRequestValidationTransfer;
    }

    protected function isPathExcludedFromValidation(GlueRequestTransfer $glueRequestTransfer): bool
    {
        return in_array($glueRequestTransfer->getPath(), $this->appKernelConfig->getOpenApiSchemaRequestValidationExcludedPaths());
    }

    protected function getMessageFromThrowable(Throwable $throwable): string
    {
        return match (get_class($throwable)) {
            InvalidBody::class => $throwable->getVerboseMessage(),
            default => $throwable->getMessage(),
        };
    }
}
