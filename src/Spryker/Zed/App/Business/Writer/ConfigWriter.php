<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\App\Business\Writer;

use Generated\Shared\Transfer\AppConfigResponseTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
use Spryker\Zed\App\Business\Sender\AsyncMessageSenderInterface;
use Spryker\Zed\App\Persistence\AppEntityManagerInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class ConfigWriter implements ConfigWriterInterface
{
    use LoggerTrait;
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\App\Business\Sender\AsyncMessageSenderInterface
     */
    protected AsyncMessageSenderInterface $asyncMessageSender;

    /**
     * @var \Spryker\Zed\App\Persistence\AppEntityManagerInterface
     */
    protected AppEntityManagerInterface $appEntityManager;

    /**
     * @param \Spryker\Zed\App\Business\Sender\AsyncMessageSenderInterface $asyncMessageSender
     * @param \Spryker\Zed\App\Persistence\AppEntityManagerInterface $appEntityManager
     */
    public function __construct(
        AsyncMessageSenderInterface $asyncMessageSender,
        AppEntityManagerInterface $appEntityManager
    ) {
        $this->asyncMessageSender = $asyncMessageSender;
        $this->appEntityManager = $appEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AppConfigResponseTransfer
     */
    public function saveConfig(AppConfigTransfer $appConfigTransfer): AppConfigResponseTransfer
    {
        $appConfigTransfer = $this->getTransactionHandler()->handleTransaction(function () use ($appConfigTransfer) {
            $appConfigTransfer = $this->doSaveAppConfig($appConfigTransfer);

            $this->sendConfigureAppCommand($appConfigTransfer);

            return $appConfigTransfer;
        });

        return $this->getSuccessResponse($appConfigTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AppConfigTransfer
     */
    protected function doSaveAppConfig(AppConfigTransfer $appConfigTransfer): AppConfigTransfer
    {
        return $this->appEntityManager->saveConfig($appConfigTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AppConfigResponseTransfer
     */
    protected function getSuccessResponse(AppConfigTransfer $appConfigTransfer): AppConfigResponseTransfer
    {
        $appResponseTransfer = new AppConfigResponseTransfer();
        $appResponseTransfer->setIsSuccessful(true)
            ->setAppConfig($appConfigTransfer);

        return $appResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     *
     * @return void
     */
    protected function sendConfigureAppCommand(AppConfigTransfer $appConfigTransfer): void
    {
        $this->asyncMessageSender->sendConfigureAppCommand($appConfigTransfer);
    }
}
