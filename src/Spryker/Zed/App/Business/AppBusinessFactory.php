<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\App\Business;

use Spryker\Zed\App\AppDependencyProvider;
use Spryker\Zed\App\Business\Deleter\ConfigDeleter;
use Spryker\Zed\App\Business\Deleter\ConfigDeleterInterface;
use Spryker\Zed\App\Business\Sender\AsyncMessageSender;
use Spryker\Zed\App\Business\Sender\AsyncMessageSenderInterface;
use Spryker\Zed\App\Business\Writer\ConfigWriter;
use Spryker\Zed\App\Business\Writer\ConfigWriterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MessageBroker\Business\MessageBrokerFacadeInterface;

/**
 * @method \Spryker\Zed\App\Persistence\AppEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\App\Persistence\AppRepositoryInterface getRepository()
 * @method \Spryker\Zed\App\AppConfig getConfig()
 */
class AppBusinessFactory extends AbstractBusinessFactory
{
 /**
  * @return \Spryker\Zed\App\Business\Writer\ConfigWriterInterface
  */
    public function createConfigWriter(): ConfigWriterInterface
    {
        return new ConfigWriter(
            $this->createAsyncMessageSender(),
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\App\Business\Deleter\ConfigDeleterInterface
     */
    public function createConfigDeleter(): ConfigDeleterInterface
    {
        return new ConfigDeleter(
            $this->getEntityManager(),
            $this->createAsyncMessageSender(),
        );
    }

    /**
     * @return \Spryker\Zed\App\Business\Sender\AsyncMessageSenderInterface
     */
    public function createAsyncMessageSender(): AsyncMessageSenderInterface
    {
        return new AsyncMessageSender(
            $this->getConfig(),
            $this->getMessageBrokerFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\MessageBroker\Business\MessageBrokerFacadeInterface
     */
    public function getMessageBrokerFacade(): MessageBrokerFacadeInterface
    {
        return $this->getProvidedDependency(AppDependencyProvider::FACADE_MESSAGE_BROKER);
    }
}
