<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AppKernel\Business\Writer;

use Codeception\Stub;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\AppConfigResponseTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\SecretTransfer;
use Spryker\Client\SecretsManager\SecretsManagerClientInterface;
use Spryker\Zed\AppKernel\Business\AppKernelBusinessFactory;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AppKernel
 * @group Business
 * @group Writer
 * @group ConfigWriterTest
 * Add your own group annotations below this line
 */
class ConfigWriterTest extends Unit
{
    use BusinessHelperTrait;
    use LocatorHelperTrait;

    /**
     * @return void
     */
    public function testSaveConfigReturnSuccessResponseWhenPropelEncryptionIsEnabled(): void
    {
        // Arrange
        $secretsManagerClientMock = Stub::makeEmpty(SecretsManagerClientInterface::class, [
            'getSecret' => function (SecretTransfer $secretsTransfer) {
                $secretsTransfer->setValue('encryption value');

                return $secretsTransfer;
            },
        ]);

        $this->getLocatorHelper()->addToLocatorCache('secretsManager-client', $secretsManagerClientMock);

        $configWriter = $this->getFactory()->createConfigWriter();
        $appConfigTransfer = new AppConfigTransfer();
        $appConfigTransfer->setConfig(['foo' => 'bar'])
            ->setTenantIdentifier('tenantIdentifier');

        // Act
        $appConfigResponseTransfer = $configWriter->saveConfig($appConfigTransfer);

        // Assert
        $this->assertInstanceOf(AppConfigResponseTransfer::class, $appConfigResponseTransfer);
    }

    /**
     * @return void
     */
    public function testSaveConfigReturnFailedResponseWhenPropelEncryptionIsEnabledButCreateSecretReturnsFalse(): void
    {
        // Arrange
        $secretsManagerClientMock = Stub::makeEmpty(SecretsManagerClientInterface::class, [
            'getSecret' => function (SecretTransfer $secretsTransfer) {
                $secretsTransfer->setValue(null);

                return $secretsTransfer;
            },
            'createSecret' => function () {
                return false;
            },
        ]);

        $this->getLocatorHelper()->addToLocatorCache('secretsManager-client', $secretsManagerClientMock);

        $configWriter = $this->getFactory()->createConfigWriter();
        $appConfigTransfer = new AppConfigTransfer();
        $appConfigTransfer->setConfig(['foo' => 'bar'])
            ->setTenantIdentifier('tenantIdentifier');

        // Act
        $appConfigResponseTransfer = $configWriter->saveConfig($appConfigTransfer);

        // Assert
        $this->assertInstanceOf(AppConfigResponseTransfer::class, $appConfigResponseTransfer);
        $this->assertFalse($appConfigResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testSaveConfigReturnSuccessResponseWhenPropelEncryptionCreateSecretReturnsTrue(): void
    {
        // Arrange
        $secretsManagerClientMock = Stub::makeEmpty(SecretsManagerClientInterface::class, [
            'getSecret' => function (SecretTransfer $secretsTransfer) {
                $secretsTransfer->setValue(null);

                return $secretsTransfer;
            },
            'createSecret' => function () {
                return true;
            },
        ]);

        $this->getLocatorHelper()->addToLocatorCache('secretsManager-client', $secretsManagerClientMock);

        $configWriter = $this->getFactory()->createConfigWriter();
        $appConfigTransfer = new AppConfigTransfer();
        $appConfigTransfer->setConfig(['foo' => 'bar'])
            ->setTenantIdentifier('tenantIdentifier');

        // Act
        $appConfigResponseTransfer = $configWriter->saveConfig($appConfigTransfer);

        // Assert
        $this->assertInstanceOf(AppConfigResponseTransfer::class, $appConfigResponseTransfer);
        $this->assertTrue($appConfigResponseTransfer->getIsSuccessful());
    }

    /**
     * @return \Spryker\Zed\AppKernel\Business\AppKernelBusinessFactory
     */
    protected function getFactory(): AppKernelBusinessFactory
    {
        return $this->getBusinessHelper()->getFactory();
    }
}
