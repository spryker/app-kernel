<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AppKernel\Validator;

use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\AppKernel\AppKernelConfig;
use Spryker\Glue\AppKernel\Dependency\Service\AppKernelToUtilEncodingServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BodyStructureValidator implements RequestValidatorInterface
{
    public function __construct(
        protected ValidatorInterface $validator,
        protected AppKernelToUtilEncodingServiceInterface $appKernelToUtilEncodingService
    ) {
    }

    public function validate(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer
    {
        $glueRequestValidationTransfer = (new GlueRequestValidationTransfer())
            ->setIsValid(true)
            ->setStatus(Response::HTTP_OK);

        $content = $this->appKernelToUtilEncodingService->decodeJson((string)$glueRequestTransfer->getContent(), true);
        $constraintViolationList = $this->validator->validate(['content' => $content], $this->getConstraintForRequestStructure());

        if ($constraintViolationList->count() > 0) {
            $glueRequestValidationTransfer
                ->setIsValid(false)
                ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

            $glueRequestValidationTransfer->addError(
                (new GlueErrorTransfer())
                    ->setCode((string)Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->setMessage(AppKernelConfig::RESPONSE_MESSAGE_VALIDATION_FORMAT_ERROR_MESSAGE),
            );
        }

        return $glueRequestValidationTransfer;
    }

    protected function getConstraintForRequestStructure(): Collection
    {
        return new Collection([
            'content' => [
                new Required(),
                new NotBlank(),
                new Collection([
                    'data' => new Collection([
                        'type' => new EqualTo(AppKernelConfig::REQUEST_DATA_TYPE),
                        'attributes' => new Collection([
                            'configuration' => [
                                new Required(),
                                new NotBlank(),
                                new Type(['type' => 'string']),
                                new Json(),
                            ],
                        ]),
                    ]),
                ]),
            ],
        ]);
    }
}
