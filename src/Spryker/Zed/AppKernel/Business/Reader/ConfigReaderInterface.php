<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Business\Reader;

use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;

interface ConfigReaderInterface
{
    public function getAppConfigByCriteria(
        AppConfigCriteriaTransfer $appConfigCriteriaTransfer
    ): AppConfigTransfer;
}
