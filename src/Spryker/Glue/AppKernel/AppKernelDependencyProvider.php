<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AppKernel;

use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Container;
use Spryker\Zed\AppKernel\Business\AppKernelFacadeInterface;

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

    public function provideBackendDependencies(Container $container): Container
    {
        $container = parent::provideBackendDependencies($container);

        $container = $this->addUtilEncodingService($container);
        $container = $this->addAppKernelFacade($container);
        $container = $this->addRequestConfigureValidatorPlugins($container);
        $container = $this->addRequestDisconnectValidatorPlugins($container);

        return $container;
    }

    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, static function (Container $container) {
            return $container->getLocator()->utilEncoding()->service();
        });

        return $container;
    }

    protected function addAppKernelFacade(Container $container): Container
    {
        $container->set(static::FACADE_APP_KERNEL, static function (Container $container): AppKernelFacadeInterface {
            return $container->getLocator()->appKernel()->facade();
        });

        return $container;
    }

    protected function addRequestConfigureValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_REQUEST_CONFIGURE_VALIDATOR, function (): array {
            return $this->getRequestConfigureValidatorPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    protected function getRequestConfigureValidatorPlugins(): array
    {
        return [];
    }

    protected function addRequestDisconnectValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_REQUEST_DISCONNECT_VALIDATOR, function (): array {
            return $this->getRequestDisconnectValidatorPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    protected function getRequestDisconnectValidatorPlugins(): array
    {
        return [];
    }
}
