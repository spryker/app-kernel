<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Communication;

use Spryker\Zed\AppKernel\AppKernelDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\AppKernel\Business\AppKernelFacadeInterface getFacade()
 * @method \Spryker\Zed\AppKernel\AppKernelConfig getConfig()
 * @method \Spryker\Zed\AppKernel\Persistence\AppKernelRepositoryInterface getRepository()
 * @method \Spryker\Zed\AppKernel\Persistence\AppKernelEntityManagerInterface getEntityManager()
 */
class AppKernelCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return list<\Spryker\Zed\AppKernelExtension\Dependency\Plugin\PostInstallTaskPluginInterface>
     */
    public function getPostInstallTaskPlugins(): array
    {
        return $this->getProvidedDependency(AppKernelDependencyProvider::PLUGINS_POST_INSTALL_TASK);
    }
}
