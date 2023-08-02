<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\App;

use Spryker\Glue\App\Plugin\RequestValidator\AppConfigValidatorPlugin;
use Spryker\Glue\App\Plugin\RequestValidator\BodyStructureValidatorPlugin;
use Spryker\Glue\App\Plugin\RequestValidator\HeaderValidatorPlugin;
use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Container as GlueBackendContainer;

class AppDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_APP = 'FACADE_APP';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const FACADE_TRANSLATOR = 'FACADE_TRANSLATOR';

    /**
     * @var string
     */
    public const PLUGINS_REQUEST_CONFIGURE_VALIDATOR = 'PLUGINS_REQUEST_CONFIGURE_VALIDATOR';

    /**
     * @var string
     */
    public const PLUGINS_REQUEST_DISCONNECT_VALIDATOR = 'PLUGINS_REQUEST_DISCONNECT_VALIDATOR';

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $glueBackendContainer
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    public function provideBackendDependencies(GlueBackendContainer $glueBackendContainer): GlueBackendContainer
    {
        $glueBackendContainer = parent::provideBackendDependencies($glueBackendContainer);

        $glueBackendContainer = $this->addUtilEncodingService($glueBackendContainer);
        $glueBackendContainer = $this->addFacadeVertexConfig($glueBackendContainer);
        $glueBackendContainer = $this->addFacadeTranslator($glueBackendContainer);
        $glueBackendContainer = $this->addRequestConfigureValidatorPlugins($glueBackendContainer);
        $glueBackendContainer = $this->addRequestDisconnectValidatorPlugins($glueBackendContainer);

        return $glueBackendContainer;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $glueBackendContainer
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addUtilEncodingService(GlueBackendContainer $glueBackendContainer): GlueBackendContainer
    {
        $glueBackendContainer->set(static::SERVICE_UTIL_ENCODING, function (GlueBackendContainer $container) {
            return $container->getLocator()->utilEncoding()->service();
        });

        return $glueBackendContainer;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $glueBackendContainer
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addFacadeVertexConfig(GlueBackendContainer $glueBackendContainer): GlueBackendContainer
    {
        $glueBackendContainer->set(static::FACADE_APP, function (GlueBackendContainer $container) {
            return $container->getLocator()->app()->facade();
        });

        return $glueBackendContainer;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $glueBackendContainer
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addFacadeTranslator(GlueBackendContainer $glueBackendContainer): GlueBackendContainer
    {
        $glueBackendContainer->set(static::FACADE_TRANSLATOR, function (GlueBackendContainer $container) {
            return $container->getLocator()->translator()->facade();
        });

        return $glueBackendContainer;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $glueBackendContainer
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addRequestConfigureValidatorPlugins(GlueBackendContainer $glueBackendContainer): GlueBackendContainer
    {
        $glueBackendContainer->set(static::PLUGINS_REQUEST_CONFIGURE_VALIDATOR, function () {
            return $this->getRequestConfigureValidatorPlugins();
        });

        return $glueBackendContainer;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $glueBackendContainer
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addRequestDisconnectValidatorPlugins(GlueBackendContainer $glueBackendContainer): GlueBackendContainer
    {
        $glueBackendContainer->set(static::PLUGINS_REQUEST_DISCONNECT_VALIDATOR, function () {
            return $this->getRequestDisconnectValidatorPlugins();
        });

        return $glueBackendContainer;
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    protected function getRequestConfigureValidatorPlugins(): array
    {
        return [
            new HeaderValidatorPlugin(),
            new BodyStructureValidatorPlugin(),
            new AppConfigValidatorPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    protected function getRequestDisconnectValidatorPlugins(): array
    {
        return [
            new HeaderValidatorPlugin(),
        ];
    }
}
