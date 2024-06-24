<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Dependency\Facade;

use Generated\Shared\Transfer\MessageResponseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

class AppKernelToMessageBrokerFacadeBridge implements AppKernelToMessageBrokerFacadeInterface
{
    /**
     * @var \Spryker\Zed\MessageBroker\Business\MessageBrokerFacadeInterface
     */
    protected $messageBrokerFacade;

    /**
     * @param \Spryker\Zed\MessageBroker\Business\MessageBrokerFacadeInterface $messageBrokerFacade
     */
    public function __construct($messageBrokerFacade)
    {
        $this->messageBrokerFacade = $messageBrokerFacade;
    }

    public function sendMessage(TransferInterface $messageTransfer): MessageResponseTransfer
    {
        return $this->messageBrokerFacade->sendMessage($messageTransfer);
    }
}
