<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Business;

use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Generated\Shared\Transfer\AppConfigResponseTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\AppDisconnectResponseTransfer;
use Generated\Shared\Transfer\AppDisconnectTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\AppKernel\Business\AppKernelBusinessFactory getFactory()
 * @method \Spryker\Zed\AppKernel\Persistence\AppKernelRepositoryInterface getRepository()
 * @method \Spryker\Zed\AppKernel\Persistence\AppKernelEntityManagerInterface getEntityManager()
 */
class AppKernelFacade extends AbstractFacade implements AppKernelFacadeInterface
{
 /**
  * {@inheritDoc}
  *
  * @api
  *
  * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
  *
  * @return \Generated\Shared\Transfer\AppConfigResponseTransfer
  */
    public function saveConfig(AppConfigTransfer $appConfigTransfer): AppConfigResponseTransfer
    {
        return $this->getFactory()
            ->createConfigWriter()
            ->saveConfig($appConfigTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AppDisconnectTransfer $appDisconnectTransfer
     *
     * @return \Generated\Shared\Transfer\AppDisconnectResponseTransfer
     */
    public function deleteConfig(AppDisconnectTransfer $appDisconnectTransfer): AppDisconnectResponseTransfer
    {
        return $this->getFactory()
            ->createConfigDeleter()
            ->deleteConfig($appDisconnectTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @template T of \Spryker\Shared\Kernel\Transfer\TransferInterface
     *
     * @param \Generated\Shared\Transfer\AppConfigCriteriaTransfer $appConfigCriteriaTransfer
     * @param T $transfer
     *
     * @return T
     */
    public function getConfig(AppConfigCriteriaTransfer $appConfigCriteriaTransfer, TransferInterface $transfer): TransferInterface
    {
        return $this->getFactory()->createConfigReader()->getAppConfigByCriteria($appConfigCriteriaTransfer, $transfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AppConfigTransfer
     */
    public function informTenantAboutChangedConfiguration(AppConfigTransfer $appConfigTransfer): AppConfigTransfer
    {
        return $this->getFactory()
            ->createMessageSender()
            ->informTenantAboutChangedConfiguration($appConfigTransfer);
    }
}
