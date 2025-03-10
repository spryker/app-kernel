<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AppKernel;

use Spryker\Glue\AppKernel\Builder\ResponseBuilder;
use Spryker\Glue\AppKernel\Builder\ResponseBuilderInterface;
use Spryker\Glue\AppKernel\Dependency\Facade\AppKernelToAppKernelFacadeInterface;
use Spryker\Glue\AppKernel\Dependency\Facade\AppKernelToTranslatorFacadeInterface;
use Spryker\Glue\AppKernel\Dependency\Service\AppKernelToUtilEncodingServiceInterface;
use Spryker\Glue\AppKernel\Mapper\GlueRequestMapper;
use Spryker\Glue\AppKernel\Mapper\GlueRequestMapperInterface;
use Spryker\Glue\AppKernel\Validator\AppConfigForTenantValidator;
use Spryker\Glue\AppKernel\Validator\BodyStructureValidator;
use Spryker\Glue\AppKernel\Validator\ConfigurationValidator;
use Spryker\Glue\AppKernel\Validator\HeaderValidator;
use Spryker\Glue\AppKernel\Validator\OpenApiRequestSchemaValidator;
use Spryker\Glue\AppKernel\Validator\RequestValidator;
use Spryker\Glue\AppKernel\Validator\RequestValidatorInterface;
use Spryker\Glue\Kernel\Backend\AbstractFactory;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @method \Spryker\Glue\AppKernel\AppKernelConfig getConfig()
 */
class AppKernelFactory extends AbstractFactory
{
    public function createHeaderValidator(): RequestValidatorInterface
    {
        return new HeaderValidator();
    }

    public function createBodyStructureValidator(): RequestValidatorInterface
    {
        return new BodyStructureValidator(
            $this->createValidator(),
            $this->getUtilEncodingService(),
        );
    }

    public function createConfigurationValidator(): RequestValidatorInterface
    {
        return new ConfigurationValidator(
            $this->createGlueRequestMapper(),
            $this->getAppKernelFacade(),
        );
    }

    public function createResponseBuilder(): ResponseBuilderInterface
    {
        return new ResponseBuilder($this->getUtilEncodingService());
    }

    public function createValidator(): ValidatorInterface
    {
        return Validation::createValidator();
    }

    public function createGlueRequestMapper(): GlueRequestMapperInterface
    {
        return new GlueRequestMapper(
            $this->getUtilEncodingService(),
        );
    }

    public function createApiRequestSaveConfigValidator(): RequestValidatorInterface
    {
        return new RequestValidator($this->getApiRequestConfigureValidatorPlugins(), [
            $this->createBodyStructureValidator(),
            $this->createHeaderValidator(),
            $this->createConfigurationValidator(),
        ]);
    }

    public function createApiRequestDisconnectValidator(): RequestValidatorInterface
    {
        return new RequestValidator($this->getApiRequestDisconnectValidatorPlugins(), [
            $this->createHeaderValidator(),
        ]);
    }

    public function getUtilEncodingService(): AppKernelToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(AppKernelDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    public function getAppKernelFacade(): AppKernelToAppKernelFacadeInterface
    {
        return $this->getProvidedDependency(AppKernelDependencyProvider::FACADE_APP_KERNEL);
    }

    public function getTranslatorFacade(): AppKernelToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(AppKernelDependencyProvider::FACADE_TRANSLATOR);
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

    public function createOpenApiRequestSchemaValidator(): OpenApiRequestSchemaValidator
    {
        return new OpenApiRequestSchemaValidator($this->getConfig());
    }

    public function createAppConfigForTenantValidator(): AppConfigForTenantValidator
    {
        return new AppConfigForTenantValidator($this->getAppKernelFacade(), $this->getConfig());
    }
}
