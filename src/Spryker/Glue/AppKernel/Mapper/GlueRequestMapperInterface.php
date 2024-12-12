<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AppKernel\Mapper;

use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\ConfigurationValidationRequestTransfer;
use Generated\Shared\Transfer\ConfigurationValidationResponseTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;

interface GlueRequestMapperInterface
{
    public function mapGlueRequestTransferToAppConfigTransfer(
        GlueRequestTransfer $glueRequestTransfer,
        AppConfigTransfer $appConfigTransfer
    ): AppConfigTransfer;

    public function mapGlueRequestTransferToConfigurationValidationRequestTransfer(
        GlueRequestTransfer $glueRequestTransfer
    ): ConfigurationValidationRequestTransfer;

    public function mapConfigurationValidationResponseTransferToGlueRequestValidationTransfer(
        ConfigurationValidationResponseTransfer $configurationValidationResponseTransfer
    ): GlueRequestValidationTransfer;
}
