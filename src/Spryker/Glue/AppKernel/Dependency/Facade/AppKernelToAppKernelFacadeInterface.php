<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AppKernel\Dependency\Facade;

use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Generated\Shared\Transfer\AppConfigResponseTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\AppDisconnectResponseTransfer;
use Generated\Shared\Transfer\AppDisconnectTransfer;

interface AppKernelToAppKernelFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\AppConfigCriteriaTransfer $appConfigCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AppConfigTransfer
     */
    public function getConfig(AppConfigCriteriaTransfer $appConfigCriteriaTransfer): AppConfigTransfer;

    /**
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AppConfigResponseTransfer
     */
    public function saveConfig(AppConfigTransfer $appConfigTransfer): AppConfigResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\AppDisconnectTransfer $appDisconnectTransfer
     *
     * @return \Generated\Shared\Transfer\AppDisconnectResponseTransfer
     */
    public function deleteConfig(AppDisconnectTransfer $appDisconnectTransfer): AppDisconnectResponseTransfer;
}
