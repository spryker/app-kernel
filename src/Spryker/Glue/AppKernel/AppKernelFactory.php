<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AppKernel;

use Spryker\Glue\AppKernel\Builder\ResponseBuilder;
use Spryker\Glue\AppKernel\Builder\ResponseBuilderInterface;
use Spryker\Glue\AppKernel\Dependency\Facade\AppKernelToAppKernelFacadeInterface;
use Spryker\Glue\AppKernel\Mapper\GlueRequestMapper;
use Spryker\Glue\AppKernel\Mapper\GlueRequestMapperInterface;
use Spryker\Glue\AppKernel\Validator\BodyStructureValidator;
use Spryker\Glue\AppKernel\Validator\HeaderValidator;
use Spryker\Glue\AppKernel\Validator\RequestValidator;
use Spryker\Glue\AppKernel\Validator\RequestValidatorInterface;
use Spryker\Glue\Kernel\Backend\AbstractFactory;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AppKernelFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\AppKernel\Validator\RequestValidatorInterface
     */
    public function createHeaderValidator(): RequestValidatorInterface
    {
        return new HeaderValidator();
    }

    /**
     * @return \Spryker\Glue\AppKernel\Validator\RequestValidatorInterface
     */
    public function createBodyStructureValidator(): RequestValidatorInterface
    {
        return new BodyStructureValidator(
            $this->createValidator(),
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Spryker\Glue\AppKernel\Builder\ResponseBuilderInterface
     */
    public function createResponseBuilder(): ResponseBuilderInterface
    {
        return new ResponseBuilder($this->getUtilEncodingService());
    }

    /**
     * @return \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    public function createValidator(): ValidatorInterface
    {
        return Validation::createValidator();
    }

    /**
     * @return \Spryker\Glue\AppKernel\Mapper\GlueRequestMapperInterface
     */
    public function createGlueRequestMapper(): GlueRequestMapperInterface
    {
        return new GlueRequestMapper(
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Spryker\Glue\AppKernel\Validator\RequestValidatorInterface
     */
    public function createApiRequestSaveConfigValidator(): RequestValidatorInterface
    {
        return new RequestValidator($this->getApiRequestConfigureValidatorPlugins());
    }

    /**
     * @return \Spryker\Glue\AppKernel\Validator\RequestValidatorInterface
     */
    public function createApiRequestDisconnectValidator(): RequestValidatorInterface
    {
        return new RequestValidator($this->getApiRequestDisconnectValidatorPlugins());
    }

    /**
     * @return \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): UtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(AppKernelDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Glue\AppKernel\Dependency\Facade\AppKernelToAppKernelFacadeInterface
     */
    public function getAppKernelFacade(): AppKernelToAppKernelFacadeInterface
    {
        return $this->getProvidedDependency(AppKernelDependencyProvider::FACADE_APP_KERNEL);
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    public function getApiRequestConfigureValidatorPlugins(): array
    {
        return $this->getProvidedDependency(AppKernelDependencyProvider::PLUGINS_REQUEST_CONFIGURE_VALIDATOR);
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    public function getApiRequestDisconnectValidatorPlugins(): array
    {
        return $this->getProvidedDependency(AppKernelDependencyProvider::PLUGINS_REQUEST_DISCONNECT_VALIDATOR);
    }
}
