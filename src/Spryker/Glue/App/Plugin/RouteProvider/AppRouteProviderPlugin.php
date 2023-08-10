<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\App\Plugin\RouteProvider;

use Spryker\Glue\App\Controller\AppConfigController;
use Spryker\Glue\App\AppConfig;
use Spryker\Glue\App\Controller\AppDisconnectController;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface;
use Spryker\Glue\Kernel\Backend\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class AppRouteProviderPlugin extends AbstractPlugin implements RouteProviderPluginInterface
{
    /**
     * @param \Symfony\Component\Routing\RouteCollection $routeCollection
     *
     * @return \Symfony\Component\Routing\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add('postConfigure', $this->getPostConfigureRoute());
        $routeCollection->add('postDisconnect', $this->getPostDisconnectRoute());

        return $routeCollection;
    }

    /**
     * @return \Symfony\Component\Routing\Route
     */
    protected function getPostConfigureRoute(): Route
    {
        return (new Route(AppConfig::CONFIGURE_ROUTE_PATH))
            ->setDefaults([
                '_controller' => [AppConfigController::class, 'postConfigureAction'],
                '_resourceName' => 'App',
            ])
            ->setMethods(Request::METHOD_POST);
    }

    /**
     * @return \Symfony\Component\Routing\Route
     */
    protected function getPostDisconnectRoute(): Route
    {
        return (new Route(AppConfig::DISCONNECT_ROUTE_PATH))
            ->setDefaults([
                '_controller' => [AppDisconnectController::class, 'postDisconnectAction'],
                '_resourceName' => 'App',
            ])
            ->setMethods(Request::METHOD_POST);
    }
}
