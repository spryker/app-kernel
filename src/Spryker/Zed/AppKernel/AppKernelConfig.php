<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel;

use Spryker\Shared\AppKernel\AppKernelConstants;
use Spryker\Shared\MessageBroker\MessageBrokerConstants;
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
     * @var string
     */
    public const APP_STATUS_DISCONNECTED = 'DISCONNECTED';

    /**
     * @api
     */
    public function getAppIdentifier(): string
    {
        return $this->get(AppKernelConstants::APP_IDENTIFIER);
    }

    /**
     * @api
     *
     * @return list<string>
     */
    public function getAppMessageChannels(): array
    {
        return $this->get(
            AppKernelConstants::APP_MESSAGE_CHANNELS,
            array_unique(array_values($this->get(MessageBrokerConstants::MESSAGE_TO_CHANNEL_MAP))),
        );
    }
}
