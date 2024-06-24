<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Business\Writer;

use Generated\Shared\Transfer\AppConfigResponseTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;

interface ConfigWriterInterface
{
    public function saveConfig(AppConfigTransfer $appConfigTransfer): AppConfigResponseTransfer;
}
