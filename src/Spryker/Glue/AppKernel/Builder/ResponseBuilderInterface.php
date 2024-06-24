<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AppKernel\Builder;

use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;

interface ResponseBuilderInterface
{
    public function buildRequestNotValidResponse(
        GlueRequestValidationTransfer $glueRequestValidationTransfer
    ): GlueResponseTransfer;

    public function buildErrorResponse(string $errorMessage): GlueResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\AppConfigTransfer|null $appConfigTransfer
     */
    public function buildSuccessfulResponse(?AppConfigTransfer $appConfigTransfer = null): GlueResponseTransfer;
}
