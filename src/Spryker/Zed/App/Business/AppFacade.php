<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\App\Business;

use Generated\Shared\Transfer\AppConfigResponseTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\AppDisconnectResponseTransfer;
use Generated\Shared\Transfer\AppDisconnectTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\App\Business\AppBusinessFactory getFactory()
 * @method \Spryker\Zed\App\Persistence\AppRepositoryInterface getRepository()
 * @method \Spryker\Zed\App\Persistence\AppEntityManagerInterface getEntityManager()
 */
class AppFacade extends AbstractFacade implements AppFacadeInterface
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
}
