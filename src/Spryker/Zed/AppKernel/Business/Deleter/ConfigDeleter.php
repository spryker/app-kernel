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
     * @param array<\Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationBeforeDeletePluginInterface> $configurationBeforeDeletePlugin
     * @param array<\Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationAfterDeletePluginInterface> $configurationAfterDeletePlugin
     */
    public function __construct(
        protected AppKernelEntityManagerInterface $appEntityManager,
        protected array $configurationBeforeDeletePlugin = [],
        protected array $configurationAfterDeletePlugin = []
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
            $this->executeConfigurationBeforeDeletePlugin($appDisconnectTransfer);

            $appConfigCriteriaTransfer = (new AppConfigCriteriaTransfer())
                ->fromArray($appDisconnectTransfer->toArray(), true);

            $numberOfDeletedRows = $this->appEntityManager->deleteConfig($appConfigCriteriaTransfer);

            if ($numberOfDeletedRows === 0) {
                return (new AppDisconnectResponseTransfer())
                    ->setIsSuccessful(false)
                    ->setErrorMessage(static::DISCONNECT_ERROR_MESSAGE);
            }

            $this->executeConfigurationAfterDeletePlugin($appDisconnectTransfer);

            return (new AppDisconnectResponseTransfer())->setIsSuccessful(true);
        } catch (Throwable $throwable) {
            return (new AppDisconnectResponseTransfer())
                ->setIsSuccessful(false)
                ->setErrorMessage(sprintf('%s: %s', static::DISCONNECT_ERROR_MESSAGE, $throwable->getMessage()));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AppDisconnectTransfer $appDisconnectTransfer
     *
     * @return void
     */
    protected function executeConfigurationBeforeDeletePlugin(AppDisconnectTransfer $appDisconnectTransfer): void
    {
        foreach ($this->configurationBeforeDeletePlugin as $configurationBeforeDeletePlugin) {
            $configurationBeforeDeletePlugin->beforeDelete($appDisconnectTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AppDisconnectTransfer $appDisconnectTransfer
     *
     * @return void
     */
    protected function executeConfigurationAfterDeletePlugin(AppDisconnectTransfer $appDisconnectTransfer): void
    {
        foreach ($this->configurationAfterDeletePlugin as $configurationAfterDeletePlugin) {
            $configurationAfterDeletePlugin->afterDelete($appDisconnectTransfer);
        }
    }
}
