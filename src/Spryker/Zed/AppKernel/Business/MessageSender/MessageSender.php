<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Business\MessageSender;

use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\AppConfigUpdatedTransfer;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Spryker\Zed\AppKernel\AppKernelConfig;
use Spryker\Zed\AppKernel\Dependency\Facade\AppKernelToMessageBrokerFacadeInterface;

class MessageSender implements MessageSenderInterface
{
    public function __construct(protected AppKernelToMessageBrokerFacadeInterface $appKernelToMessageBrokerFacade, protected AppKernelConfig $appKernelConfig)
    {
    }

    public function sendAppConfigUpdatedMessage(AppConfigTransfer $appConfigTransfer): AppConfigTransfer
    {
        $appConfigUpdatedTransfer = new AppConfigUpdatedTransfer();
        $appConfigUpdatedTransfer->fromArray($appConfigTransfer->toArray(), true);
        $appConfigUpdatedTransfer
            ->setMessageChannels($this->appKernelConfig->getAppMessageChannels())
            ->setAppIdentifier($this->appKernelConfig->getAppIdentifier());

        $appConfigUpdatedTransfer->setMessageAttributes($this->getMessageAttributes(
            $appConfigTransfer->getTenantIdentifierOrFail(),
            $appConfigTransfer::class,
        ));

        $this->appKernelToMessageBrokerFacade->sendMessage($appConfigUpdatedTransfer);

        return $appConfigTransfer;
    }

    protected function getMessageAttributes(string $tenantIdentifier, string $transferName): MessageAttributesTransfer
    {
        $messageAttributesTransfer = new MessageAttributesTransfer();
        $messageAttributesTransfer
            ->setActorId($this->appKernelConfig->getAppIdentifier())
            ->setEmitter($this->appKernelConfig->getAppIdentifier())
            ->setTenantIdentifier($tenantIdentifier)
            ->setStoreReference($tenantIdentifier)
            ->setTransferName($transferName);

        return $messageAttributesTransfer;
    }
}
