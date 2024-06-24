<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\AppConfigTransfer;
use Orm\Zed\AppKernel\Persistence\SpyAppConfig;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
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
        TransferInterface $transfer
    ): TransferInterface {
        $decodedAppConfig = (array)$this->appKernelToUtilEncodingService->decodeJson($spyAppConfig->getConfig(), true);
        $transfer->fromArray(
            (array)$this->appKernelToUtilEncodingService->decodeJson($spyAppConfig->getConfig(), true),
            true,
        );

        if ($transfer instanceof AppConfigTransfer) {
            $transfer->fromArray($spyAppConfig->toArray(), true);
            $transfer->setConfig($decodedAppConfig);
        }

        return $transfer;
    }
}
