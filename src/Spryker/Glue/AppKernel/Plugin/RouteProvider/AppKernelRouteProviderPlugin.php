<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AppKernel\Plugin\RouteProvider;

use Spryker\Glue\AppKernel\AppKernelConfig;
use Spryker\Glue\AppKernel\Controller\AppConfigController;
use Spryker\Glue\AppKernel\Controller\AppDisconnectController;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface;
use Spryker\Glue\Kernel\Backend\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class AppKernelRouteProviderPlugin extends AbstractPlugin implements RouteProviderPluginInterface
{
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add('postConfigure', $this->getPostConfigureRoute());
        $routeCollection->add('postDisconnect', $this->getPostDisconnectRoute());

        return $routeCollection;
    }

    protected function getPostConfigureRoute(): Route
    {
        return (new Route(AppKernelConfig::CONFIGURE_ROUTE_PATH))
            ->setDefaults([
                '_controller' => [AppConfigController::class, 'postConfigureAction'],
                '_resourceName' => 'App',
            ])
            ->setMethods(Request::METHOD_POST);
    }

    protected function getPostDisconnectRoute(): Route
    {
        return (new Route(AppKernelConfig::DISCONNECT_ROUTE_PATH))
            ->setDefaults([
                '_controller' => [AppDisconnectController::class, 'postDisconnectAction'],
                '_resourceName' => 'App',
            ])
            ->setMethods(Request::METHOD_POST);
    }
}
