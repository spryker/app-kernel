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
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Validator\Constraints\NotBlank;
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

        $constraintViolationList = $this->validator->validate($glueRequestTransfer->getContent(), $this->getConstrainForRequestContent());

        if ($constraintViolationList->count() > 0) {
            return $this->getFailedGlueRequestValidationTransfer($glueRequestValidationTransfer);
        }

        $content = $this->appKernelToUtilEncodingService->decodeJson((string)$glueRequestTransfer->getContent(), true);
        $constraintViolationList = $this->validator->validate($content, $this->getConstraintForRequestStructure());

        if ($constraintViolationList->count() > 0) {
            return $this->getFailedGlueRequestValidationTransfer($glueRequestValidationTransfer);
        }

        return $glueRequestValidationTransfer;
    }

    protected function getConstrainForRequestContent(): Constraint
    {
        return new All([
            new Required(),
            new NotBlank(),
            new Type(['type' => 'string']),
            new Json(),
        ]);
    }

    protected function getConstraintForRequestStructure(): Constraint
    {
        return new Collection([
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
        ]);
    }

    protected function getFailedGlueRequestValidationTransfer(
        GlueRequestValidationTransfer $glueRequestValidationTransfer
    ): GlueRequestValidationTransfer {
        $glueRequestValidationTransfer
            ->setIsValid(false)
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        return $glueRequestValidationTransfer->addError(
            (new GlueErrorTransfer())
                ->setCode((string)Response::HTTP_UNPROCESSABLE_ENTITY)
                ->setMessage(AppKernelConfig::RESPONSE_MESSAGE_VALIDATION_FORMAT_ERROR_MESSAGE),
        );
    }
}
