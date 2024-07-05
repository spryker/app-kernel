<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\AppConfigTransfer;
use Orm\Zed\AppKernel\Persistence\SpyAppConfig;
use Spryker\Zed\AppKernel\Dependency\Service\AppKernelToUtilEncodingServiceInterface;

class AppConfigMapper
{
    public function __construct(protected AppKernelToUtilEncodingServiceInterface $appKernelToUtilEncodingService)
    {
    }

    public function mapAppConfigTransferToAppConfigEntity(
        AppConfigTransfer $appConfigTransfer,
        SpyAppConfig $spyAppConfig
    ): SpyAppConfig {
        $appConfigArray = $appConfigTransfer->modifiedToArray();
        $appConfigArray[AppConfigTransfer::CONFIG] = $this->appKernelToUtilEncodingService->encodeJson($appConfigTransfer->getConfig());

        $spyAppConfig->fromArray($appConfigArray);

        return $spyAppConfig;
    }

    public function mapAppConfigEntityToAppConfigTransfer(
        SpyAppConfig $spyAppConfig,
        AppConfigTransfer $appConfigTransfer
    ): AppConfigTransfer {
        // Decode the existing SpyAppConfig::CONFIG JSON string to an array.
        $decodedAppConfig = (array)$this->appKernelToUtilEncodingService->decodeJson($spyAppConfig->getConfig(), true);
        $persistedData = $spyAppConfig->toArray();

        $appConfigTransfer->fromArray($persistedData, true);
        $appConfigTransfer->setConfig($decodedAppConfig);

        return $appConfigTransfer;
    }
}
