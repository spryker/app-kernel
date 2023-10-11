<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AppKernel\Validator;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;

class RequestValidator implements RequestValidatorInterface
{
    /**
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface> $requestValidatorPlugins
     */
    public function __construct(protected array $requestValidatorPlugins)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validate(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer
    {
        foreach ($this->requestValidatorPlugins as $validatorPlugin) {
            $glueRequestValidationTransfer = $validatorPlugin->validate($glueRequestTransfer);

            if (!$glueRequestValidationTransfer->getIsValid()) {
                return $glueRequestValidationTransfer;
            }
        }

        return (new GlueRequestValidationTransfer())->setIsValid(true);
    }
}
