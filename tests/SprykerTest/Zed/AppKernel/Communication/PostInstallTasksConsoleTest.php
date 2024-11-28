<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AppKernel\Communication;

use Codeception\Test\Unit;
use Spryker\Zed\AppKernel\AppKernelDependencyProvider;
use Spryker\Zed\AppKernel\Communication\Console\PostInstallTasksConsole;
use Spryker\Zed\AppKernelExtension\Dependency\Plugin\PostInstallTaskPluginInterface;
use Spryker\Zed\Kernel\Communication\Console\Console;
use SprykerTest\Zed\AppKernel\AppKernelCommunicationTester;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AppKernel
 * @group Communication
 * @group PostInstallTasksConsoleTest
 * Add your own group annotations below this line
 */
class PostInstallTasksConsoleTest extends Unit
{
    protected AppKernelCommunicationTester $tester;

    /**
     * @return void
     */
    public function testPostInstallTasksConsoleExecutesPostInstallTaskPlugins(): void
    {
        // Arrange
        $application = new Application();
        $application->add(new PostInstallTasksConsole());

        $command = $application->find(PostInstallTasksConsole::COMMAND_NAME);
        $commandTester = new CommandTester($command);

        $postInstallTaskPluginMock = $this->createMock(PostInstallTaskPluginInterface::class);
        $postInstallTaskPluginMock->expects($this->once())->method('run');

        $this->tester->setDependency(AppKernelDependencyProvider::PLUGINS_POST_INSTALL_TASK, [$postInstallTaskPluginMock]);

        // Act
        $commandTester->execute([]);

        // Assert
        $this->assertSame(Console::CODE_SUCCESS, $commandTester->getStatusCode());
    }
}
