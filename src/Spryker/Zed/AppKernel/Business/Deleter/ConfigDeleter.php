<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Business\Deleter;

use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Generated\Shared\Transfer\AppDisconnectResponseTransfer;
use Generated\Shared\Transfer\AppDisconnectTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\AppKernel\Persistence\AppKernelEntityManagerInterface;
use Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationAfterDeletePluginInterface;
use Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationBeforeDeletePluginInterface;
use Throwable;

class ConfigDeleter implements ConfigDeleterInterface
{
    use LoggerTrait;

    /**
     * @var string
     */
    protected const DISCONNECT_ERROR_MESSAGE = 'Tenant disconnection failed';

    /**
     * @param \Spryker\Zed\AppKernel\Persistence\AppKernelEntityManagerInterface $appEntityManager
     * @param \Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationBeforeDeletePluginInterface|null $configurationBeforeDeletePlugin
     * @param \Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationAfterDeletePluginInterface|null $configurationAfterDeletePlugin
     */
    public function __construct(
        protected AppKernelEntityManagerInterface $appEntityManager,
        protected ?ConfigurationBeforeDeletePluginInterface $configurationBeforeDeletePlugin = null,
        protected ?ConfigurationAfterDeletePluginInterface $configurationAfterDeletePlugin = null
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\AppDisconnectTransfer $appDisconnectTransfer
     *
     * @return \Generated\Shared\Transfer\AppDisconnectResponseTransfer
     */
    public function deleteConfig(AppDisconnectTransfer $appDisconnectTransfer): AppDisconnectResponseTransfer
    {
        try {
            if ($this->configurationBeforeDeletePlugin) {
                $appDisconnectTransfer = $this->configurationBeforeDeletePlugin->beforeDelete($appDisconnectTransfer);
            }

            $appConfigCriteriaTransfer = (new AppConfigCriteriaTransfer())
                ->fromArray($appDisconnectTransfer->toArray(), true);

            $numberOfDeletedRows = $this->appEntityManager->deleteConfig($appConfigCriteriaTransfer);

            if ($numberOfDeletedRows === 0) {
                return (new AppDisconnectResponseTransfer())
                    ->setIsSuccessful(false)
                    ->setErrorMessage(static::DISCONNECT_ERROR_MESSAGE);
            }

            if ($this->configurationAfterDeletePlugin) {
                $this->configurationAfterDeletePlugin->afterDelete($appDisconnectTransfer);
            }

            return (new AppDisconnectResponseTransfer())->setIsSuccessful(true);
        } catch (Throwable $throwable) {
            return (new AppDisconnectResponseTransfer())
                ->setIsSuccessful(false)
                ->setErrorMessage(sprintf('%s: %s', static::DISCONNECT_ERROR_MESSAGE, $throwable->getMessage()));
        }
    }
}
