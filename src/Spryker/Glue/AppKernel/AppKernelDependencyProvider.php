<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AppKernel;

use Spryker\Glue\AppKernel\Dependency\Facade\AppKernelToAppKernelFacadeBridge;
use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Container as GlueBackendContainer;

/**
 * @method \Spryker\Glue\AppKernel\AppKernelConfig getConfig()
 */
class AppKernelDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_APP_KERNEL = 'FACADE_APP_KERNEL';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const PLUGINS_REQUEST_CONFIGURE_VALIDATOR = 'PLUGINS_REQUEST_CONFIGURE_VALIDATOR';

    /**
     * @var string
     */
    public const PLUGINS_REQUEST_DISCONNECT_VALIDATOR = 'PLUGINS_REQUEST_DISCONNECT_VALIDATOR';

    public function provideBackendDependencies(GlueBackendContainer $glueBackendContainer): GlueBackendContainer
    {
        $glueBackendContainer = parent::provideBackendDependencies($glueBackendContainer);

        $glueBackendContainer = $this->addUtilEncodingService($glueBackendContainer);
        $glueBackendContainer = $this->addAppKernelFacade($glueBackendContainer);
        $glueBackendContainer = $this->addRequestConfigureValidatorPlugins($glueBackendContainer);
        $glueBackendContainer = $this->addRequestDisconnectValidatorPlugins($glueBackendContainer);

        return $glueBackendContainer;
    }

    protected function addUtilEncodingService(GlueBackendContainer $glueBackendContainer): GlueBackendContainer
    {
        $glueBackendContainer->set(static::SERVICE_UTIL_ENCODING, static function (GlueBackendContainer $glueBackendContainer) {
            return $glueBackendContainer->getLocator()->utilEncoding()->service();
        });

        return $glueBackendContainer;
    }

    protected function addAppKernelFacade(GlueBackendContainer $glueBackendContainer): GlueBackendContainer
    {
        $glueBackendContainer->set(static::FACADE_APP_KERNEL, static function (GlueBackendContainer $glueBackendContainer): AppKernelToAppKernelFacadeBridge {
            return new AppKernelToAppKernelFacadeBridge($glueBackendContainer->getLocator()->appKernel()->facade());
        });

        return $glueBackendContainer;
    }

    protected function addRequestConfigureValidatorPlugins(GlueBackendContainer $glueBackendContainer): GlueBackendContainer
    {
        $glueBackendContainer->set(static::PLUGINS_REQUEST_CONFIGURE_VALIDATOR, function (): array {
            return array_merge($this->getRequestConfigureValidatorPlugins());
        });

        return $glueBackendContainer;
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    protected function getRequestConfigureValidatorPlugins(): array
    {
        return [];
    }

    protected function addRequestDisconnectValidatorPlugins(GlueBackendContainer $glueBackendContainer): GlueBackendContainer
    {
        $glueBackendContainer->set(static::PLUGINS_REQUEST_DISCONNECT_VALIDATOR, function (): array {
            return array_merge($this->getRequestDisconnectValidatorPlugins());
        });

        return $glueBackendContainer;
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    protected function getRequestDisconnectValidatorPlugins(): array
    {
        return [];
    }
}
