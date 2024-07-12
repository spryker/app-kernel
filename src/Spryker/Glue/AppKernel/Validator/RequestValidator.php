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
     * @param array<\Spryker\Glue\AppKernel\Validator\RequestValidatorInterface> $defaultRequestValidatorPlugins
     */
    public function __construct(protected array $requestValidatorPlugins, protected array $defaultRequestValidatorPlugins = [])
    {
    }

    public function validate(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer
    {
        $validators = array_merge($this->defaultRequestValidatorPlugins, $this->requestValidatorPlugins);

        foreach ($validators as $validator) {
            $glueRequestValidationTransfer = $validator->validate($glueRequestTransfer);

            if (!$glueRequestValidationTransfer->getIsValid()) {
                return $glueRequestValidationTransfer;
            }
        }

        return (new GlueRequestValidationTransfer())->setIsValid(true);
    }
}
