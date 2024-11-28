<?php

namespace Spryker\Zed\AppKernel\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\AppKernel\Communication\AppKernelCommunicationFactory getFactory()
 */
class PostInstallTasksConsole extends Console
{
    public const COMMAND_NAME = 'setup:post-install';

    public const DESCRIPTION = "Executes post install tasks.\nDespite the name, this command is not intended to be used in Spryker post-deploy hook `SPRYKER_HOOK_AFTER_DEPLOY` as it is not executed in the context of the application.";

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::DESCRIPTION);
    }

    /**
     * @return int
     */
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
