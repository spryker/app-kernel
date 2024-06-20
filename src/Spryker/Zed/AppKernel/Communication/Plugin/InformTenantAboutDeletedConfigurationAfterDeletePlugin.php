<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Communication\Plugin;

use Generated\Shared\Transfer\AppDisconnectTransfer;
use Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationAfterDeletePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\AppKernel\Business\AppKernelFacadeInterface getFacade()
 * @method \Spryker\Zed\AppKernel\AppKernelConfig getConfig()
 */
class InformTenantAboutDeletedConfigurationAfterDeletePlugin extends AbstractPlugin implements ConfigurationAfterDeletePluginInterface
{
    /**
     * {@inheritDoc}
     * - Provided to update the configuration to the tenant.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AppDisconnectTransfer $appDisconnectTransfer
     *
     * @return \Generated\Shared\Transfer\AppDisconnectTransfer
     */
    public function afterDelete(AppDisconnectTransfer $appDisconnectTransfer): AppDisconnectTransfer
    {
        return $this->getFacade()->informTenantAboutDeletedConfiguration($appDisconnectTransfer);
    }
}
