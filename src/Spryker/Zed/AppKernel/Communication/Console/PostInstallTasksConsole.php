<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\AppKernel\Communication\AppKernelCommunicationFactory getFactory()
 * @method \Spryker\Zed\AppKernel\Business\AppKernelFacadeInterface getFacade()
 * @method \Spryker\Zed\AppKernel\Persistence\AppKernelRepositoryInterface getRepository()
 */
class PostInstallTasksConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'setup:post-install';

    /**
     * @var string
     */
    public const DESCRIPTION = "Executes post install tasks.\nDespite the name, this command is not intended to be used in Spryker post-deploy hook `SPRYKER_HOOK_AFTER_DEPLOY` as it is not executed in the context of the application.";

    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::DESCRIPTION);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $postInstallTaskPlugins = $this->getFactory()->getPostInstallTaskPlugins();

        $this->info(sprintf('Executing post install tasks (%s).', count($postInstallTaskPlugins)));

        foreach ($postInstallTaskPlugins as $postInstallTaskPlugin) {
            $postInstallTaskPlugin->run($input, $output);
        }

        $this->info('Post install tasks executed successfully.');

        return static::CODE_SUCCESS;
    }
}
