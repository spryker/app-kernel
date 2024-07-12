<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\AppKernel\Validator;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\AppKernel\Mapper\GlueRequestMapperInterface;
use Spryker\Zed\AppKernel\Business\AppKernelFacadeInterface;

/**
 * @method \Spryker\Glue\AppKernel\AppKernelFactory getFactory()
 */
class ConfigurationValidator implements RequestValidatorInterface
{
    public function __construct(
        protected GlueRequestMapperInterface $glueRequestMapper,
        protected AppKernelFacadeInterface $appKernelFacade
    ) {
    }

    public function validate(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer
    {
        $configurationValidationRequestTransfer = $this->glueRequestMapper->mapGlueRequestTransferToConfigurationValidationRequestTransfer($glueRequestTransfer);
        $configurationValidationResponseTransfer = $this->appKernelFacade->validateConfiguration($configurationValidationRequestTransfer);

        return $this->glueRequestMapper->mapConfigurationValidationResponseTransferToGlueRequestValidationTransfer($configurationValidationResponseTransfer);
    }
}
