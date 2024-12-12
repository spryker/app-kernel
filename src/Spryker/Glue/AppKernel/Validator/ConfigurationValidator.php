<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\AppKernel\Validator;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\AppKernel\Dependency\Facade\AppKernelToAppKernelFacadeInterface;
use Spryker\Glue\AppKernel\Mapper\GlueRequestMapperInterface;

/**
 * @method \Spryker\Glue\AppKernel\AppKernelFactory getFactory()
 */
class ConfigurationValidator implements RequestValidatorInterface
{
    public function __construct(
        protected GlueRequestMapperInterface $glueRequestMapper,
        protected AppKernelToAppKernelFacadeInterface $appKernelToAppKernelFacade
    ) {
    }

    public function validate(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer
    {
        $configurationValidationRequestTransfer = $this->glueRequestMapper->mapGlueRequestTransferToConfigurationValidationRequestTransfer($glueRequestTransfer);
        $configurationValidationResponseTransfer = $this->appKernelToAppKernelFacade->validateConfiguration($configurationValidationRequestTransfer);

        return $this->glueRequestMapper->mapConfigurationValidationResponseTransferToGlueRequestValidationTransfer($configurationValidationResponseTransfer);
    }
}
