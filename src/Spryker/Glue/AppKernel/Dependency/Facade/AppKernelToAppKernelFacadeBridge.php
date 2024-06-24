<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AppKernel\Dependency\Facade;

use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Generated\Shared\Transfer\AppConfigResponseTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;

class AppKernelToAppKernelFacadeBridge implements AppKernelToAppKernelFacadeInterface
{
    /**
     * @var \Spryker\Zed\AppKernel\Business\AppKernelFacadeInterface
     */
    protected $appKernelFacade;

    /**
     * @param \Spryker\Zed\AppKernel\Business\AppKernelFacadeInterface $appKernelFacade
     */
    public function __construct($appKernelFacade)
    {
        $this->appKernelFacade = $appKernelFacade;
    }

    public function getConfig(AppConfigCriteriaTransfer $appConfigCriteriaTransfer): AppConfigTransfer
    {
        return $this->appKernelFacade->getConfig($appConfigCriteriaTransfer, new AppConfigTransfer());
    }

    public function saveConfig(AppConfigTransfer $appConfigTransfer): AppConfigResponseTransfer
    {
        return $this->appKernelFacade->saveConfig($appConfigTransfer);
    }
}
