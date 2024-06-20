<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Communication\Plugin;

use Generated\Shared\Transfer\AppConfigTransfer;
use Spryker\Zed\AppKernelExtension\Dependency\Plugin\ConfigurationAfterSavePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\AppKernel\Business\AppKernelFacadeInterface getFacade()
 * @method \Spryker\Zed\AppKernel\AppKernelConfig getConfig()
 */
class InformTenantAboutChangedConfigurationAfterSavePlugin extends AbstractPlugin implements ConfigurationAfterSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Provided to update the configuration to the tenant.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AppConfigTransfer
     */
    public function afterSave(AppConfigTransfer $appConfigTransfer): AppConfigTransfer
    {
        return $this->getFacade()->informTenantAboutChangedConfiguration($appConfigTransfer);
    }
}
