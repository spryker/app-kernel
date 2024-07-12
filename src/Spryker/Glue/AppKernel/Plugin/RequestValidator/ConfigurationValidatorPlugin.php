<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\AppKernel\Plugin\RequestValidator;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\AppKernel\AppKernelFactory getFactory()
 */
class ConfigurationValidatorPlugin extends AbstractPlugin implements RequestValidatorPluginInterface
{
    public function validate(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer
    {
        return $this->getFactory()->getAppKernelFacade()->validateConfiguration($glueRequestTransfer);
    }
}
