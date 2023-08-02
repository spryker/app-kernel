<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\App\Builder;

use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;

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
