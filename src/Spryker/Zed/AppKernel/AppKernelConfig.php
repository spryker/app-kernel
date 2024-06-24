<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel;

use Spryker\Shared\AppKernel\AppKernelConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class AppKernelConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const APP_STATUS_NEW = 'NEW';

    /**
     * @var string
     */
    public const APP_STATUS_CONNECTED = 'CONNECTED';

    /**
     * @api
     */
    public function getAppIdentifier(): string
    {
        return $this->get(AppKernelConstants::APP_IDENTIFIER);
    }
}
