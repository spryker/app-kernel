<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\App\Business\Deleter;

use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Generated\Shared\Transfer\AppConfigResponseTransfer;
use Generated\Shared\Transfer\AppDisconnectResponseTransfer;
use Generated\Shared\Transfer\AppDisconnectTransfer;
use Spryker\Zed\App\Business\Sender\AsyncMessageSenderInterface;
use Spryker\Zed\App\Persistence\AppEntityManagerInterface;
use Spryker\Shared\Log\LoggerTrait;

class ConfigDeleter implements ConfigDeleterInterface
{
    use LoggerTrait;

    /**
     * @var string
     */
    protected const DISCONNECT_ERROR_MESSAGE = 'Failed to disconnect tenant';

    /**
     * @var \Spryker\Zed\App\Persistence\AppEntityManagerInterface
     */
    protected AppEntityManagerInterface $appEntityManager;

    /**
     * @var \Spryker\Zed\App\Business\Sender\AsyncMessageSenderInterface
     */
    protected AsyncMessageSenderInterface $asyncMessageSender;

    /**
     * @param \Spryker\Zed\App\Persistence\AppEntityManagerInterface $appEntityManager
     * @param \Spryker\Zed\App\Business\Sender\AsyncMessageSenderInterface $asyncMessageSender
     */
    public function __construct(
        AppEntityManagerInterface $appEntityManager,
        AsyncMessageSenderInterface $asyncMessageSender
    ) {
        $this->appEntityManager = $appEntityManager;
        $this->asyncMessageSender = $asyncMessageSender;
    }

    /**
     * @param \Generated\Shared\Transfer\AppDisconnectTransfer $appDisconnectTransfer
     *
     * @return \Generated\Shared\Transfer\AppDisconnectResponseTransfer
     */
    public function deleteConfig(AppDisconnectTransfer $appDisconnectTransfer): AppDisconnectResponseTransfer
    {
        $appConfigCriteriaTransfer = (new AppConfigCriteriaTransfer())
            ->fromArray($appDisconnectTransfer->toArray(), true);

        $numberOfDeletedRows = $this->appEntityManager->deleteConfig($appConfigCriteriaTransfer);

        if ($numberOfDeletedRows === 0) {
            return (new AppDisconnectResponseTransfer())
                ->setIsSuccessful(false)
                ->setErrorMessage(static::DISCONNECT_ERROR_MESSAGE);
        }

        $this->asyncMessageSender->sendDeleteAppConfigurationCommand($appDisconnectTransfer->getStoreReference());

        return (new AppDisconnectResponseTransfer())->setIsSuccessful(true);
    }
}
