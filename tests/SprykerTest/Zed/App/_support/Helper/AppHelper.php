<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\App\Helper;

use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Generated\Shared\DataBuilder\AppConfigBuilder;
use Generated\Shared\DataBuilder\AppDisconnectBuilder;
use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\AppDisconnectTransfer;
use Orm\Zed\App\Persistence\SpyAppConfig;
use Spryker\Zed\MessageBroker\Business\MessageBrokerFacade;
use Spryker\Zed\MessageBroker\Business\MessageBrokerFacadeInterface;

class AppHelper extends Module
{
    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\AppConfigTransfer
     */
    public function haveAppConfigTransfer(array $seed = []): AppConfigTransfer
    {
        return (new AppConfigBuilder($seed))->build();
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\AppDisconnectTransfer
     */
    public function haveAppDisconnectTransfer(array $seed = []): AppDisconnectTransfer
    {
        return (new AppDisconnectBuilder($seed))->build();
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\AppConfigTransfer
     */
    public function havePersistedAppConfigTransfer(array $seed = []): AppConfigTransfer
    {
        $appConfigTransfer = $this->haveAppConfigTransfer($seed);

        $appConfigEntity = new SpyAppConfig();
        $appConfigEntity->fromArray($appConfigTransfer->modifiedToArray());
        $appConfigEntity->save();

        return $appConfigTransfer;
    }
}
