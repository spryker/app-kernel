<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AppKernel\Dependency\Facade;

use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Generated\Shared\Transfer\AppConfigResponseTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;

interface AppKernelToAppKernelFacadeInterface
{
    public function getConfig(AppConfigCriteriaTransfer $appConfigCriteriaTransfer): AppConfigTransfer;

    public function saveConfig(AppConfigTransfer $appConfigTransfer): AppConfigResponseTransfer;
}
