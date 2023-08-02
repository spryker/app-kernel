<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\App;

use Spryker\Glue\App\Builder\ResponseBuilder;
use Spryker\Glue\App\Builder\ResponseBuilderInterface;
use Spryker\Glue\App\Mapper\GlueRequestMapper;
use Spryker\Glue\App\Mapper\GlueRequestMapperInterface;
use Spryker\Glue\App\Validator\BodyStructureValidator;
use Spryker\Glue\App\Validator\HeaderValidator;
use Spryker\Glue\App\Validator\RequestValidator;
use Spryker\Glue\App\Validator\RequestValidatorInterface;
use Spryker\Glue\App\Validator\AppConfigValidator;
use Spryker\Zed\App\Business\AppFacadeInterface;
use Spryker\Glue\Kernel\Backend\AbstractFactory;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Zed\Translator\Business\TranslatorFacadeInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AppFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): UtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(AppDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\App\Business\AppFacadeInterface
     */
    public function getAppFacade(): AppFacadeInterface
    {
        return $this->getProvidedDependency(AppDependencyProvider::FACADE_APP);
    }

    /**
     * @return \Spryker\Glue\App\Validator\RequestValidatorInterface
     */
    public function createHeaderValidator(): RequestValidatorInterface
    {
        return new HeaderValidator($this->getTranslatorFacade());
    }

    /**
     * @return \Spryker\Glue\App\Validator\RequestValidatorInterface
     */
    public function createBodyStructureValidator(): RequestValidatorInterface
    {
        return new BodyStructureValidator(
            $this->createValidator(),
            $this->getUtilEncodingService(),
            $this->getTranslatorFacade(),
        );
    }

    /**
     * @return \Spryker\Glue\App\Validator\RequestValidatorInterface
     */
    public function createAppConfigValidator(): RequestValidatorInterface
    {
        return new AppConfigValidator(
            $this->createValidator(),
            $this->getUtilEncodingService(),
            $this->getTranslatorFacade(),
        );
    }

    /**
     * @return \Spryker\Glue\App\Builder\ResponseBuilderInterface
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
     * @return \Spryker\Glue\App\Mapper\GlueRequestMapperInterface
     */
    public function createGlueRequestMapper(): GlueRequestMapperInterface
    {
        return new GlueRequestMapper(
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Spryker\Zed\Translator\Business\TranslatorFacadeInterface
     */
    public function getTranslatorFacade(): TranslatorFacadeInterface
    {
        return $this->getProvidedDependency(AppDependencyProvider::FACADE_TRANSLATOR);
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    public function getApiRequestConfigureValidatorPlugins(): array
    {
        return $this->getProvidedDependency(AppDependencyProvider::PLUGINS_REQUEST_CONFIGURE_VALIDATOR);
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    public function getApiRequestDisconnectValidatorPlugins(): array
    {
        return $this->getProvidedDependency(AppDependencyProvider::PLUGINS_REQUEST_DISCONNECT_VALIDATOR);
    }

    /**
     * @return \Spryker\Glue\App\Validator\RequestValidatorInterface
     */
    public function createApiRequestSaveConfigValidator(): RequestValidatorInterface
    {
        return new RequestValidator($this->getApiRequestConfigureValidatorPlugins());
    }

    /**
     * @return \Spryker\Glue\App\Validator\RequestValidatorInterface
     */
    public function createApiRequestDisconnectValidator(): RequestValidatorInterface
    {
        return new RequestValidator($this->getApiRequestDisconnectValidatorPlugins());
    }
}
