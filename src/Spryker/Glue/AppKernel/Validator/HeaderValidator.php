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
use Symfony\Component\HttpFoundation\Response;

class HeaderValidator implements RequestValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validate(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer
    {
        $glueRequestValidationTransfer = (new GlueRequestValidationTransfer())
            ->setIsValid(true)->setStatus(Response::HTTP_OK);

        $meta = $glueRequestTransfer->getMeta();

        if (empty($meta[AppKernelConfig::HEADER_TENANT_IDENTIFIER][0])) {
            $glueRequestValidationTransfer
                ->setIsValid(false)
                ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->addError(
                    (new GlueErrorTransfer())
                        ->setCode((string)Response::HTTP_UNPROCESSABLE_ENTITY)
                        ->setMessage(AppKernelConfig::RESPONSE_MESSAGE_MISSING_TENANT_IDENTIFIER),
                );
        }

        return $glueRequestValidationTransfer;
    }
}
