<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AppKernel\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\AppConfigBuilder;
use Generated\Shared\DataBuilder\AppConfigUpdatedBuilder;
use Generated\Shared\DataBuilder\AppDisconnectBuilder;
use Generated\Shared\DataBuilder\MyAppConfigBuilder;
use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\AppConfigUpdatedTransfer;
use Generated\Shared\Transfer\AppDisconnectTransfer;
use Generated\Shared\Transfer\MyAppConfigTransfer;
use Orm\Zed\AppKernel\Persistence\SpyAppConfig;

class AppKernelHelper extends Module
{
    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\AppConfigTransfer
     */
    public function haveAppConfigTransfer(array $seed = []): AppConfigTransfer
    {
        if (!isset($seed[AppConfigTransfer::CONFIG])) {
            $seed[AppConfigTransfer::CONFIG] = [];
        }

        return (new AppConfigBuilder($seed))->build();
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\MyAppConfigTransfer
     */
    public function haveMyAppConfigTransfer(array $seed = []): MyAppConfigTransfer
    {
        return (new MyAppConfigBuilder($seed))->build();
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
     * @return \Generated\Shared\Transfer\AppConfigUpdatedTransfer
     */
    public function haveAppConfigUpdatedTransfer(array $seed = []): AppConfigUpdatedTransfer
    {
        return (new AppConfigUpdatedBuilder($seed))->build();
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\AppConfigTransfer
     */
    public function havePersistedAppConfigTransfer(array $seed = []): AppConfigTransfer
    {
        $appConfigTransfer = $this->haveAppConfigTransfer($seed);

        // The AppConfigTransfer::CONFIG is an array, but we need to have a BLOB in the database. For this we convert the config from an array to a JSON string.
        $appConfigArray = $appConfigTransfer->modifiedToArray();
        $appConfigArray[AppConfigTransfer::CONFIG] = json_encode((array)$appConfigTransfer->getConfig());

        $appConfigEntity = new SpyAppConfig();
        $appConfigEntity->fromArray($appConfigArray);
        $appConfigEntity->save();

        return $appConfigTransfer;
    }
}
