<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\AppKernel\Plugin\GlueApplication;

use Codeception\Stub;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\AppKernel\AppKernelConfig;
use Spryker\Glue\AppKernel\AppKernelFactory;
use Spryker\Glue\AppKernel\Plugin\GlueApplication\AppGlueRequestSchemaValidatorPlugin;
use SprykerTest\Glue\AppKernel\AppKernelTester;
use SprykerTest\Glue\Testify\Helper\DependencyProviderHelperTrait;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group AppKernel
 * @group Plugin
 * @group GlueApplication
 * @group AppGlueRequestSchemaValidatorPluginTest
 * Add your own group annotations below this line
 */
class AppGlueRequestSchemaValidatorPluginTest extends Unit
{
    use DependencyProviderHelperTrait;

    protected AppKernelTester $tester;

    public function testGivenAValidOpenApiSchemaWhenTheRequestedMatchesTheSchemaThenAValidValidationResponseIsReturned(): void
    {
        // Arrange
        $appKernelConfigStub = Stub::make(AppKernelConfig::class, [
            'getOpenApiSchemaPath' => codecept_data_dir('Fixtures/OpenApi/valid-openapi-schema.yml'),
        ]);

        $appKernelFactory = Stub::make(AppKernelFactory::class, [
            'getConfig' => $appKernelConfigStub,
        ]);

        $appGlueRequestSchemaValidatorPlugin = new AppGlueRequestSchemaValidatorPlugin();
        $appGlueRequestSchemaValidatorPlugin->setFactory($appKernelFactory);

        $glueRequestTransfer = new GlueRequestTransfer();
        $glueRequestTransfer
            ->setMethod('POST')
            ->setPath('/existing-endpoint')
            ->setMeta([
                'X-Tenant-Identifier' => 'tenant-identifier',
                'Content-Type' => 'application/json',
            ])
            ->setContent('{"data": {"attributes": {"foo": "bar"}}}');

        // Act
        $glueRequestValidationTransfer = $appGlueRequestSchemaValidatorPlugin->validate($glueRequestTransfer);

        // Assert
        $this->assertTrue($glueRequestValidationTransfer->getIsValid());
    }

    public function testGivenNoOpenApiSchemaFileDefinedWhenTheValidationIsExecutedThenAValidValidationResponseIsReturned(): void
    {
        // Arrange
        $appGlueRequestSchemaValidatorPlugin = new AppGlueRequestSchemaValidatorPlugin();

        $glueRequestTransfer = new GlueRequestTransfer();

        // Act
        $glueRequestValidationTransfer = $appGlueRequestSchemaValidatorPlugin->validate($glueRequestTransfer);

        // Assert
        $this->assertTrue($glueRequestValidationTransfer->getIsValid());
    }

    public function testGivenAnEndpointThatWouldThrowAnExceptionAndItIsExcludedFromTheValidationWhenTheRequestIsValidatedThenAValidValidationResponseIsReturned(): void
    {
        // Arrange
        $appKernelConfigStub = Stub::make(AppKernelConfig::class, [
            'getValidationExcludedPaths' => ['/non-existing-endpoint'],
        ]);

        $appKernelFactory = Stub::make(AppKernelFactory::class, [
            'getConfig' => $appKernelConfigStub,
        ]);

        $appGlueRequestSchemaValidatorPlugin = new AppGlueRequestSchemaValidatorPlugin();
        $appGlueRequestSchemaValidatorPlugin->setFactory($appKernelFactory);

        $glueRequestTransfer = new GlueRequestTransfer();
        $glueRequestTransfer
            ->setMethod('GET')
            ->setPath('/non-existing-endpoint');

        // Act
        $glueRequestValidationTransfer = $appGlueRequestSchemaValidatorPlugin->validate($glueRequestTransfer);

        // Assert
        $this->assertTrue($glueRequestValidationTransfer->getIsValid());
    }

    public function testGivenAValidOpenApiSchemaWhenTheRequestedPathIsNotDefinedThenAnInValidValidationResponseIsReturned(): void
    {
        // Arrange
        $appKernelConfigStub = Stub::make(AppKernelConfig::class, [
            'getOpenApiSchemaPath' => codecept_data_dir('Fixtures/OpenApi/valid-openapi-schema.yml'),
        ]);

        $appKernelFactory = Stub::make(AppKernelFactory::class, [
            'getConfig' => $appKernelConfigStub,
        ]);

        $appGlueRequestSchemaValidatorPlugin = new AppGlueRequestSchemaValidatorPlugin();
        $appGlueRequestSchemaValidatorPlugin->setFactory($appKernelFactory);

        $glueRequestTransfer = new GlueRequestTransfer();
        $glueRequestTransfer
            ->setMethod('GET')
            ->setPath('/non-existing-endpoint');

        // Act
        $glueRequestValidationTransfer = $appGlueRequestSchemaValidatorPlugin->validate($glueRequestTransfer);

        // Assert
        $this->assertFalse($glueRequestValidationTransfer->getIsValid());
        $this->assertSame('OpenAPI spec contains no such operation [/non-existing-endpoint]', $glueRequestValidationTransfer->getErrors()[0]->getMessage());
    }

    public function testGivenAValidOpenApiSchemaWhenTheRequestDoesNotHaveARequiredHeaderThenAnInValidValidationResponseIsReturned(): void
    {
        // Arrange
        $appKernelConfigStub = Stub::make(AppKernelConfig::class, [
            'getOpenApiSchemaPath' => codecept_data_dir('Fixtures/OpenApi/valid-openapi-schema.yml'),
        ]);

        $appKernelFactory = Stub::make(AppKernelFactory::class, [
            'getConfig' => $appKernelConfigStub,
        ]);

        $appGlueRequestSchemaValidatorPlugin = new AppGlueRequestSchemaValidatorPlugin();
        $appGlueRequestSchemaValidatorPlugin->setFactory($appKernelFactory);

        $glueRequestTransfer = new GlueRequestTransfer();
        $glueRequestTransfer
            ->setMethod('GET')
            ->setPath('/existing-endpoint');

        // Act
        $glueRequestValidationTransfer = $appGlueRequestSchemaValidatorPlugin->validate($glueRequestTransfer);

        // Assert
        $this->assertFalse($glueRequestValidationTransfer->getIsValid());
        $this->assertSame('Missing required header "X-Tenant-Identifier" for Request [get /existing-endpoint]', $glueRequestValidationTransfer->getErrors()[0]->getMessage());
    }

    public function testGivenAValidOpenApiSchemaWhenTheRequestDoesNotHaveTheContentTypeHeaderSetThenAnInValidValidationResponseIsReturned(): void
    {
        // Arrange
        $appKernelConfigStub = Stub::make(AppKernelConfig::class, [
            'getOpenApiSchemaPath' => codecept_data_dir('Fixtures/OpenApi/valid-openapi-schema.yml'),
        ]);

        $appKernelFactory = Stub::make(AppKernelFactory::class, [
            'getConfig' => $appKernelConfigStub,
        ]);

        $appGlueRequestSchemaValidatorPlugin = new AppGlueRequestSchemaValidatorPlugin();
        $appGlueRequestSchemaValidatorPlugin->setFactory($appKernelFactory);

        $glueRequestTransfer = new GlueRequestTransfer();
        $glueRequestTransfer
            ->setMethod('POST')
            ->setPath('/existing-endpoint')
            ->setMeta([
                'X-Tenant-Identifier' => 'tenant-identifier',
            ]);

        // Act
        $glueRequestValidationTransfer = $appGlueRequestSchemaValidatorPlugin->validate($glueRequestTransfer);

        // Assert
        $this->assertFalse($glueRequestValidationTransfer->getIsValid());
        $this->assertSame('Missing required header "Content-Type" for Request [post /existing-endpoint]', $glueRequestValidationTransfer->getErrors()[0]->getMessage());
    }

    public function testGivenAValidOpenApiSchemaWhenTheRequestDoesNotHaveABodyThenAnInValidValidationResponseIsReturned(): void
    {
        // Arrange
        $appKernelConfigStub = Stub::make(AppKernelConfig::class, [
            'getOpenApiSchemaPath' => codecept_data_dir('Fixtures/OpenApi/valid-openapi-schema.yml'),
        ]);

        $appKernelFactory = Stub::make(AppKernelFactory::class, [
            'getConfig' => $appKernelConfigStub,
        ]);

        $appGlueRequestSchemaValidatorPlugin = new AppGlueRequestSchemaValidatorPlugin();
        $appGlueRequestSchemaValidatorPlugin->setFactory($appKernelFactory);

        $glueRequestTransfer = new GlueRequestTransfer();
        $glueRequestTransfer
            ->setMethod('POST')
            ->setPath('/existing-endpoint')
            ->setMeta([
                'X-Tenant-Identifier' => 'tenant-identifier',
                'Content-Type' => 'application/json',
            ]);

        // Act
        $glueRequestValidationTransfer = $appGlueRequestSchemaValidatorPlugin->validate($glueRequestTransfer);

        // Assert
        $this->assertFalse($glueRequestValidationTransfer->getIsValid());
        $this->assertSame('JSON parsing failed with "Syntax error" for Request [post /existing-endpoint]', $glueRequestValidationTransfer->getErrors()[0]->getMessage());
    }

    public function testGivenAValidOpenApiSchemaWhenTheRequestDoesNotHaveARequiredFieldThenAnInValidValidationResponseIsReturned(): void
    {
        // Arrange
        $appKernelConfigStub = Stub::make(AppKernelConfig::class, [
            'getOpenApiSchemaPath' => codecept_data_dir('Fixtures/OpenApi/valid-openapi-schema.yml'),
        ]);

        $appKernelFactory = Stub::make(AppKernelFactory::class, [
            'getConfig' => $appKernelConfigStub,
        ]);

        $appGlueRequestSchemaValidatorPlugin = new AppGlueRequestSchemaValidatorPlugin();
        $appGlueRequestSchemaValidatorPlugin->setFactory($appKernelFactory);

        $glueRequestTransfer = new GlueRequestTransfer();
        $glueRequestTransfer
            ->setMethod('POST')
            ->setPath('/existing-endpoint')
            ->setMeta([
                'X-Tenant-Identifier' => 'tenant-identifier',
                'Content-Type' => 'application/json',
            ])
            ->setContent('{"key": "value"}');

        // Act
        $glueRequestValidationTransfer = $appGlueRequestSchemaValidatorPlugin->validate($glueRequestTransfer);

        // Assert
        $this->assertFalse($glueRequestValidationTransfer->getIsValid());
        $this->assertSame('Body does not match schema for content-type "application/json" for Request [post /existing-endpoint]. [Keyword validation failed: Required property \'data\' must be present in the object in data]', $glueRequestValidationTransfer->getErrors()[0]->getMessage());
    }

    public function testGivenAValidOpenApiSchemaWhenTheRequestDoesNotHaveARequiredFieldInTheSecondLevelThenAnInValidValidationResponseIsReturned(): void
    {
        // Arrange
        $appKernelConfigStub = Stub::make(AppKernelConfig::class, [
            'getOpenApiSchemaPath' => codecept_data_dir('Fixtures/OpenApi/valid-openapi-schema.yml'),
        ]);

        $appKernelFactory = Stub::make(AppKernelFactory::class, [
            'getConfig' => $appKernelConfigStub,
        ]);

        $appGlueRequestSchemaValidatorPlugin = new AppGlueRequestSchemaValidatorPlugin();
        $appGlueRequestSchemaValidatorPlugin->setFactory($appKernelFactory);

        $glueRequestTransfer = new GlueRequestTransfer();
        $glueRequestTransfer
            ->setMethod('POST')
            ->setPath('/existing-endpoint')
            ->setMeta([
                'X-Tenant-Identifier' => 'tenant-identifier',
                'Content-Type' => 'application/json',
            ])
            ->setContent('{"data": {"foo": "bar"}}');

        // Act
        $glueRequestValidationTransfer = $appGlueRequestSchemaValidatorPlugin->validate($glueRequestTransfer);

        // Assert
        $this->assertFalse($glueRequestValidationTransfer->getIsValid());
        $this->assertSame('Body does not match schema for content-type "application/json" for Request [post /existing-endpoint]. [Keyword validation failed: Required property \'attributes\' must be present in the object in data->attributes]', $glueRequestValidationTransfer->getErrors()[0]->getMessage());
    }
}
