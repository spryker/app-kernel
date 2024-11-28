<?php

namespace Spryker\Zed\AppKernel\Communication\Plugin\AppKernel;

use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Spryker\Zed\AppKernelExtension\Dependency\Plugin\PostInstallTaskPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\AppKernel\Business\AppKernelFacadeInterface getFacade()
 * @method \Spryker\Zed\AppKernel\Persistence\AppKernelRepositoryInterface getRepository()
 */
class TriggerAppConfigUpdatePostInstallTaskPlugin extends AbstractPlugin implements PostInstallTaskPluginInterface
{
    /**
     * {@inheritDoc}
     * - Triggers AppConfig update for all connected tenants.
     *
     * @api
     */
    public function run(InputInterface $input, OutputInterface $output): void
    {
        $tenantIdentifiers = $this->getRepository()->getConnectedTenantIdentifiers();

        $output->writeln(sprintf('Triggering AppConfig update for all connected tenants (%s).', count($tenantIdentifiers)));

        foreach ($tenantIdentifiers as $tenantIdentifier) {
            $this->triggerAppConfigUpdate($tenantIdentifier);
        }
    }

    protected function triggerAppConfigUpdate(string $tenantIdentifier): void
    {
        $appConfigCriteriaTransfer = (new AppConfigCriteriaTransfer())
            ->setTenantIdentifier($tenantIdentifier)
            ->setIsActive(true);

        $appConfigTransfer = $this->getFacade()->getConfig($appConfigCriteriaTransfer);
        $this->getFacade()->saveConfig($appConfigTransfer);
    }
}
