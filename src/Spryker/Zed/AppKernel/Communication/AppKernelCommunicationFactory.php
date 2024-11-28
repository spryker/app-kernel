<?php

namespace Spryker\Zed\AppKernel\Communication;

use Spryker\Zed\AppKernel\AppKernelDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

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
