<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Business\MessageSender;

use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\AppDisconnectTransfer;
use Generated\Shared\Transfer\AppStatusChangedTransfer;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Spryker\Zed\AppKernel\AppKernelConfig;
use Spryker\Zed\AppKernel\Dependency\Facade\AppKernelToMessageBrokerFacadeInterface;

class MessageSender implements MessageSenderInterface
{
    /**
     * @var \Spryker\Zed\AppKernel\Dependency\Facade\AppKernelToMessageBrokerFacadeInterface
     */
    private AppKernelToMessageBrokerFacadeInterface $messageBrokerFacade;

    /**
     * @var \Spryker\Zed\AppKernel\AppKernelConfig
     */
    protected AppKernelConfig $config;

    /**
     * @param \Spryker\Zed\AppKernel\Dependency\Facade\AppKernelToMessageBrokerFacadeInterface $messageBrokerFacade
     * @param \Spryker\Zed\AppKernel\AppKernelConfig $config
     */
    public function __construct(AppKernelToMessageBrokerFacadeInterface $messageBrokerFacade, AppKernelConfig $config)
    {
        $this->messageBrokerFacade = $messageBrokerFacade;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AppConfigTransfer
     */
    public function informTenantAboutChangedConfiguration(AppConfigTransfer $appConfigTransfer): AppConfigTransfer
    {
        $status = false;
        if (
            $appConfigTransfer->getStatus() === AppKernelConfig::APP_STATUS_CONNECTED &&
            ($appConfigTransfer->getIsActive() === null || $appConfigTransfer->getIsActive() === true)
        ) {
            $status = true;
        }
        $appStatusChangedTransfer = new AppStatusChangedTransfer();
        $appStatusChangedTransfer
            ->setAppIdentifier($this->config->getAppIdentifier())
            ->setStatus($status);

        $appStatusChangedTransfer->setMessageAttributes($this->getMessageAttributes(
            $appConfigTransfer->getTenantIdentifierOrFail(),
            $appConfigTransfer::class,
        ));

        $this->messageBrokerFacade->sendMessage($appStatusChangedTransfer);

        return $appConfigTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AppDisconnectTransfer $appDisconnectTransfer
     *
     * @return \Generated\Shared\Transfer\AppConfigTransfer
     */
    public function informTenantAboutDeletedConfiguration(AppDisconnectTransfer $appDisconnectTransfer): AppConfigTransfer
    {
        $appStatusChangedTransfer = new AppStatusChangedTransfer();
        $appStatusChangedTransfer
            ->setAppIdentifier($this->config->getAppIdentifier())
            ->setStatus(false);

        $appStatusChangedTransfer->setMessageAttributes($this->getMessageAttributes(
            $appDisconnectTransfer->getTenantIdentifierOrFail(),
            $appStatusChangedTransfer::class,
        ));

        $this->messageBrokerFacade->sendMessage($appStatusChangedTransfer);

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
