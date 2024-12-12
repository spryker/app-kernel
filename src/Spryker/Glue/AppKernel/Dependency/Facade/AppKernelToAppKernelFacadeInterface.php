<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AppKernel\Dependency\Facade;

use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Generated\Shared\Transfer\AppConfigResponseTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\ConfigurationValidationRequestTransfer;
use Generated\Shared\Transfer\ConfigurationValidationResponseTransfer;

interface AppKernelToAppKernelFacadeInterface
{
    public function validateConfiguration(
        ConfigurationValidationRequestTransfer $configurationValidationRequestTransfer
    ): ConfigurationValidationResponseTransfer;

    public function getConfig(AppConfigCriteriaTransfer $appConfigCriteriaTransfer): AppConfigTransfer;

    public function saveConfig(AppConfigTransfer $appConfigTransfer): AppConfigResponseTransfer;
}
