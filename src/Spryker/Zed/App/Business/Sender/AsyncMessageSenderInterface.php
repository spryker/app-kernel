<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\App\Business\Sender;

use Generated\Shared\Transfer\AppConfigTransfer;

interface AsyncMessageSenderInterface
{
    /**
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     *
     * @return void
     */
    public function sendConfigureAppCommand(AppConfigTransfer $appConfigTransfer): void;

    /**
     * @param string $storeReference
     *
     * @return void
     */
    public function sendDeleteAppConfigurationCommand(string $storeReference): void;
}
