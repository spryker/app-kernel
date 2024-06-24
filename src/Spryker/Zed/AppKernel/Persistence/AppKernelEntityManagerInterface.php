<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Persistence;

use Generated\Shared\Transfer\AppConfigTransfer;

interface AppKernelEntityManagerInterface
{
    public function saveConfig(AppConfigTransfer $appConfigTransfer): AppConfigTransfer;
}
