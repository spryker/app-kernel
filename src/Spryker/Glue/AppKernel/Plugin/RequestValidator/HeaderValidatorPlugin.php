<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AppKernel\Plugin\RequestValidator;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface;
use Spryker\Glue\Kernel\Backend\AbstractPlugin;

/**
 * @method \Spryker\Glue\AppKernel\AppKernelFactory getFactory()
 */
class HeaderValidatorPlugin extends AbstractPlugin implements RequestValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if X-Tenant-Identifier is present in headers
     *
     * @api
     */
    public function validate(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer
    {
        return $this->getFactory()->createHeaderValidator()->validate($glueRequestTransfer);
    }
}
