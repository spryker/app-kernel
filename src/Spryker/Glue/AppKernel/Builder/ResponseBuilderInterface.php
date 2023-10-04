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
    /**
     * @param \Generated\Shared\Transfer\GlueRequestValidationTransfer $glueRequestValidationTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function buildRequestNotValidResponse(
        GlueRequestValidationTransfer $glueRequestValidationTransfer
    ): GlueResponseTransfer;

    /**
     * @param string $errorMessage
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function buildErrorResponse(string $errorMessage): GlueResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\AppConfigTransfer|null $appConfigTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function buildSuccessfulResponse(?AppConfigTransfer $appConfigTransfer = null): GlueResponseTransfer;
}
