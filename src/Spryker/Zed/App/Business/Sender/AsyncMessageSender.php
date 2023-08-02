<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\App\Business\Sender;

use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\ConfigureAppTransfer;
use Generated\Shared\Transfer\DeleteAppTransfer;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Spryker\Zed\App\AppConfig;
use Spryker\Zed\MessageBroker\Business\MessageBrokerFacadeInterface;

class AsyncMessageSender implements AsyncMessageSenderInterface
{
    /**
     * @var \Spryker\Zed\App\AppConfig
     */
    protected AppConfig $appConfig;

    /**
     * @var \Spryker\Zed\MessageBroker\Business\MessageBrokerFacadeInterface
     */
    protected MessageBrokerFacadeInterface $messageBrokerFacade;

    /**
     * @param \Spryker\Zed\App\AppConfig $appConfig
     * @param \Spryker\Zed\MessageBroker\Business\MessageBrokerFacadeInterface $messageBrokerFacade
     */
    public function __construct(
        AppConfig $appConfig,
        MessageBrokerFacadeInterface $messageBrokerFacade
    ) {
        $this->appConfig = $appConfig;
        $this->messageBrokerFacade = $messageBrokerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     *
     * @return void
     */
    public function sendConfigureAppCommand(AppConfigTransfer $appConfigTransfer): void
    {
        $messageAttributes = $this->createMessageAttributes($appConfigTransfer->getStoreReference());

        $configureTaxAppTransfer = (new ConfigureAppTransfer())
            ->setMessageAttributes($messageAttributes)
            ->setApiUrl($this->appConfig->getAppUrl())
            ->setAppIdentifier($this->appConfig->getAppIdentifier());

        $this->messageBrokerFacade->sendMessage($configureTaxAppTransfer);
    }

    /**
     * @param string $storeReference
     *
     * @return void
     */
    public function sendDeleteAppConfigurationCommand(string $storeReference): void
    {
        $messageAttributes = $this->createMessageAttributes($storeReference);

        $deleteTaxAppTransfer = (new DeleteAppTransfer())
            ->setMessageAttributes($messageAttributes)
            ->setAppIdentifier($this->appConfig->getAppIdentifier());

        $this->messageBrokerFacade->sendMessage($deleteTaxAppTransfer);
    }

    /**
     * @param string $storeReference
     *
     * @return \Generated\Shared\Transfer\MessageAttributesTransfer
     */
    protected function createMessageAttributes(string $storeReference): MessageAttributesTransfer
    {
        return (new MessageAttributesTransfer())
            ->setStoreReference($storeReference)
            ->setEmitter($this->appConfig->getAppIdentifier());
    }
}
