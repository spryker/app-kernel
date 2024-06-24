<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Business\MessageSender;

use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\AppConfigUpdatedTransfer;
use Generated\Shared\Transfer\AppDisconnectTransfer;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Spryker\Zed\AppKernel\AppKernelConfig;
use Spryker\Zed\AppKernel\Dependency\Facade\AppKernelToMessageBrokerFacadeInterface;

class MessageSender implements MessageSenderInterface
{
    /**
     * @param \Spryker\Zed\AppKernel\Dependency\Facade\AppKernelToMessageBrokerFacadeInterface $messageBrokerFacade
     * @param \Spryker\Zed\AppKernel\AppKernelConfig $config
     */
    public function __construct(protected AppKernelToMessageBrokerFacadeInterface $messageBrokerFacade, protected AppKernelConfig $config)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AppConfigTransfer
     */
    public function informTenantAboutChangedConfiguration(AppConfigTransfer $appConfigTransfer): AppConfigTransfer
    {
        $appConfigUpdatedTransfer = new AppConfigUpdatedTransfer();
        $appConfigUpdatedTransfer->fromArray($appConfigTransfer->toArray(), true);
        $appConfigUpdatedTransfer
            ->setAppIdentifier($this->config->getAppIdentifier());

        $appConfigUpdatedTransfer->setMessageAttributes($this->getMessageAttributes(
            $appConfigTransfer->getTenantIdentifierOrFail(),
            $appConfigTransfer::class,
        ));

        $this->messageBrokerFacade->sendMessage($appConfigUpdatedTransfer);

        return $appConfigTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AppDisconnectTransfer $appDisconnectTransfer
     *
     * @return \Generated\Shared\Transfer\AppDisconnectTransfer
     */
    public function informTenantAboutDeletedConfiguration(AppDisconnectTransfer $appDisconnectTransfer): AppDisconnectTransfer
    {
        $appConfigUpdatedTransfer = new AppConfigUpdatedTransfer();
        $appConfigUpdatedTransfer
            ->setAppIdentifier($this->config->getAppIdentifier())
            ->setIsActive(false);

        $appConfigUpdatedTransfer->setMessageAttributes($this->getMessageAttributes(
            $appDisconnectTransfer->getTenantIdentifierOrFail(),
            $appConfigUpdatedTransfer::class,
        ));

        $this->messageBrokerFacade->sendMessage($appConfigUpdatedTransfer);

        return $appDisconnectTransfer;
    }

    /**
     * @param string $tenantIdentifier
     * @param string $transferName
     *
     * @return \Generated\Shared\Transfer\MessageAttributesTransfer
     */
    protected function getMessageAttributes(string $tenantIdentifier, string $transferName): MessageAttributesTransfer
    {
        $messageAttributesTransfer = new MessageAttributesTransfer();
        $messageAttributesTransfer
            ->setActorId($this->config->getAppIdentifier())
            ->setEmitter($this->config->getAppIdentifier())
            ->setTenantIdentifier($tenantIdentifier)
            ->setStoreReference($tenantIdentifier)
            ->setTransferName($transferName);

        return $messageAttributesTransfer;
    }
}
