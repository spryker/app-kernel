<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Business;

use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Generated\Shared\Transfer\AppConfigResponseTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\ConfigurationValidationRequestTransfer;
use Generated\Shared\Transfer\ConfigurationValidationResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\AppKernel\Business\AppKernelBusinessFactory getFactory()
 * @method \Spryker\Zed\AppKernel\Persistence\AppKernelRepositoryInterface getRepository()
 * @method \Spryker\Zed\AppKernel\Persistence\AppKernelEntityManagerInterface getEntityManager()
 */
class AppKernelFacade extends AbstractFacade implements AppKernelFacadeInterface
{
    /**
     * @api
     *
     * @inheritDoc
     */
    public function validateConfiguration(
        ConfigurationValidationRequestTransfer $configurationValidationRequestTransfer
    ): ConfigurationValidationResponseTransfer {
        return $this->getFactory()->createConfigurationValidator()->validateConfiguration($configurationValidationRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
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
     */
    public function getConfig(AppConfigCriteriaTransfer $appConfigCriteriaTransfer): AppConfigTransfer
    {
        return $this->getFactory()->createConfigReader()->getAppConfigByCriteria($appConfigCriteriaTransfer);
    }
}
