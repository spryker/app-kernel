<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\App\Persistence;

use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;

interface AppEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AppConfigTransfer
     */
    public function saveConfig(AppConfigTransfer $appConfigTransfer): AppConfigTransfer;

    /**
     * @param \Generated\Shared\Transfer\AppConfigCriteriaTransfer $appConfigCriteriaTransfer
     *
     * @return int Affected number of deleted rows
     */
    public function deleteConfig(AppConfigCriteriaTransfer $appConfigCriteriaTransfer): int;
}
