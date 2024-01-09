<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AppKernel\Business\Reader;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Client\SecretsManager\SecretsManagerClient;
use Spryker\Zed\AppKernel\AppKernelDependencyProvider;
use Spryker\Zed\AppKernel\Dependency\Client\AppKernelToSecretsManagerClientBridge;
use Spryker\Zed\AppKernel\Persistence\AppKernelRepository;
use Spryker\Zed\Kernel\Container;
use SprykerTest\Zed\AppKernel\AppKernelBusinessTester;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AppKernel
 * @group Business
 * @group Reader
 * @group ConfigReaderTest
 * Add your own group annotations below this line
 */
class ConfigReaderTest extends Unit
{
    use BusinessHelperTrait;

    protected AppKernelBusinessTester $tester;

    /**
     * @return void
     */
    public function testGetAppConfigByCriteriaThrowsAnExceptionWhichIsCaughtAndLoggedAndReturnsAppConfigWithoutEncryptedData(): void
    {
        // Arrange
        $tenantIdentifier = Uuid::uuid4()->toString();
        $seed = [
            AppConfigTransfer::TENANT_IDENTIFIER => $tenantIdentifier,
            AppConfigTransfer::CONFIG => ['foo' => 'foo', 'bar' => 123],
        ];

        $this->tester->havePersistedAppConfigTransfer($seed);

        $appConfigCriteriaTransfer = new AppConfigCriteriaTransfer();
        $appConfigCriteriaTransfer->setTenantIdentifier($tenantIdentifier);

        /** @var \Spryker\Zed\AppKernel\Business\AppKernelBusinessFactory $factory */
        $factory = $this->getBusinessHelper()->mockFactoryMethod('getSecretsManagerClient', function () {
            return new AppKernelToSecretsManagerClientBridge(new SecretsManagerClient());
        });
        $factory->setRepository(new AppKernelRepository());
        $factory->setContainer((new AppKernelDependencyProvider())->provideBusinessLayerDependencies(new Container()));

        $configReader = $factory->createConfigReader();

        // Act
        $appConfigTransfer = $configReader->getAppConfigByCriteria($appConfigCriteriaTransfer, new AppConfigTransfer());

        // Assert
        $this->assertInstanceOf(AppConfigTransfer::class, $appConfigTransfer);
        $this->assertIsArray($appConfigTransfer->getConfig());
    }
}
