<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Testify\Helper;

use Codeception\Stub;
use Codeception\Util\HttpCode;
use Spryker\Glue\AppKernel\Plugin\RouteProvider\AppKernelRouteProviderPlugin;
use Spryker\Glue\GlueApplication\GlueApplicationDependencyProvider;
use Spryker\Glue\GlueApplication\GlueApplicationFactory;
use Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationDependencyProvider;
use Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationFactory;
use Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication\ApplicationIdentifierRequestBuilderPlugin;
use Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication\ControllerCacheCollectorPlugin as BackendControllerCacheCollectorPlugin;
use Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication\CustomRouteRoutesProviderPlugin;
use Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication\ResourcesProviderPlugin as BackendResourcesProviderPlugin;
use Spryker\Glue\GlueJsonApiConvention\Plugin\GlueApplication\JsonApiConventionPlugin;
use Spryker\Shared\Application\ApplicationInterface;
use SprykerTest\Glue\Testify\Helper\GlueApplication\Bootstrap\GlueBackendApiBootstrap;
use SprykerTest\Glue\Testify\Helper\GlueBackendApiHelper as SprykerGlueBackendApiHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AppKernelGlueBackendApiHelper extends SprykerGlueBackendApiHelper
{
    /**
     * @param array<mixed, mixed>|string $parameters
     */
    protected function executeRequest(string $url, string $method, array $parameters = []): Response
    {
        $this->addHeader('Accept', 'application/json');

        $request = Request::create($url, strtolower($method), $parameters, [], [], [], $parameters !== [] ? json_encode($parameters, JSON_PRESERVE_ZERO_FRACTION | JSON_THROW_ON_ERROR) : null);
        $request = $this->removeServerAndHeaderDefaults($request);

        $request->headers->add($this->headers);

        // Set the predefined Request so that the GlueBackendApiApplication can pick it up instead of creating an empty Request.
        $this->getRequestBuilderStub()->setRequest($request);

        // Run the mocked GlueBackendApiApplication.
        $this->getGlueBackendApiApplication()->run();

        // Get the response that was created from the GlueBackendApiApplication.
        $response = $this->getHttpSenderStub()->getResponse();

        $this->persistLastConnection($request, $response);

        return $response;
    }

    /**
     * The Request::create() method adds some default headers and server values that we do not want to have in our tests.
     */
    protected function removeServerAndHeaderDefaults(Request $request): Request
    {
        foreach (['HTTP_ACCEPT', 'HTTP_ACCEPT_LANGUAGE', 'HTTP_ACCEPT_CHARSET'] as $server) {
            $request->server->remove($server);
        }
        foreach (['accept', 'accept-language', 'accept-charset'] as $header) {
            $request->headers->remove($header);
        }

        return $request;
    }

    protected function getGlueBackendApiApplication(): ApplicationInterface
    {
        /** @var \Spryker\Glue\GlueApplication\GlueApplicationFactory $glueApplicationFactory */
        $glueApplicationFactory = Stub::make(GlueApplicationFactory::class, [
            'createHttpRequestBuilder' => $this->getRequestBuilderStub(),
            'createHttpSender' => $this->getHttpSenderStub(),
            'resolveDependencyProvider' => new GlueApplicationDependencyProvider(),
            'getConfig' => $this->getConfigHelper()->getModuleConfig('GlueApplication'),
        ]);

        $this->getDependencyProviderHelper()->setDependency(GlueBackendApiApplicationDependencyProvider::PLUGINS_REQUEST_BUILDER, [
            new ApplicationIdentifierRequestBuilderPlugin(),
        ], GlueBackendApiApplicationFactory::class);

        $this->getDependencyProviderHelper()->setDependency(GlueBackendApiApplicationDependencyProvider::PLUGINS_ROUTE_PROVIDER, [
            new AppKernelRouteProviderPlugin(),
        ], GlueBackendApiApplicationFactory::class);

        $this->getDependencyProviderHelper()->setDependency(GlueApplicationDependencyProvider::PLUGINS_RESOURCES_PROVIDER, [
            new BackendResourcesProviderPlugin(),
        ], get_class($glueApplicationFactory));

        $this->getDependencyProviderHelper()->setDependency(GlueApplicationDependencyProvider::PLUGINS_CONVENTION, [
            new JsonApiConventionPlugin(),
        ], get_class($glueApplicationFactory));

        $this->getDependencyProviderHelper()->setDependency(GlueApplicationDependencyProvider::PLUGINS_ROUTES_PROVIDER, [
            new CustomRouteRoutesProviderPlugin(),
        ], get_class($glueApplicationFactory));

        $this->getDependencyProviderHelper()->setDependency(GlueApplicationDependencyProvider::PLUGINS_CONTROLLER_CACHE_COLLECTOR, [
            new BackendControllerCacheCollectorPlugin(),
        ], get_class($glueApplicationFactory));

        $this->getDependencyProviderHelper()->setDependency(
            GlueBackendApiApplicationDependencyProvider::PLUGINS_RESOURCE,
            $this->getJsonApiResourcePlugins(),
            GlueBackendApiApplicationFactory::class,
        );

        return (new GlueBackendApiBootstrap())
            ->setFactory($glueApplicationFactory)
            ->boot();
    }

    /**
     * @param int $code
     *
     * @return void
     */
    public function seeResponseCodeIs(int $code): void
    {
        $failureMessage = sprintf(
            'Expected HTTP Status Code: %s. Actual Status Code: %s. Response: %s',
            HttpCode::getDescription($code),
            HttpCode::getDescription($this->getResponse()->getStatusCode()),
            $this->getResponse(),
        );
        $this->assertSame($code, $this->getResponse()->getStatusCode(), $failureMessage);
    }

    /**
     * Overridden to not throw an exception when we are on project level and do not need to set plugins manually.
     *
     * @return array<\Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface>
     */
    protected function getJsonApiResourcePlugins(): array
    {
        if ($this->jsonApiResourcePlugins === []) {
            return [];
        }

        return $this->jsonApiResourcePlugins;
    }
}
