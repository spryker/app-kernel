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
    /**
     * @param \Spryker\Zed\AppKernel\Dependency\Service\AppKernelToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(protected AppKernelToUtilEncodingServiceInterface $utilEncodingService)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     * @param \Orm\Zed\AppKernel\Persistence\SpyAppConfig $appConfigEntity
     *
     * @return \Orm\Zed\AppKernel\Persistence\SpyAppConfig
     */
    public function mapAppConfigTransferToAppConfigEntity(
        AppConfigTransfer $appConfigTransfer,
        SpyAppConfig $appConfigEntity
    ): SpyAppConfig {
        $appConfigArray = $appConfigTransfer->modifiedToArray();
        $appConfigArray[AppConfigTransfer::CONFIG] = $this->utilEncodingService->encodeJson($appConfigTransfer->getConfig());

        $appConfigEntity->fromArray($appConfigArray);

        return $appConfigEntity;
    }

    /**
     * @param \Orm\Zed\AppKernel\Persistence\SpyAppConfig $appConfigEntity
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function mapAppConfigEntityToAppConfigTransfer(
        SpyAppConfig $appConfigEntity,
        TransferInterface $transfer
    ): TransferInterface {
        $decodedAppConfig = (array)$this->utilEncodingService->decodeJson($appConfigEntity->getConfig(), true);
        $transfer->fromArray(
            (array)$this->utilEncodingService->decodeJson($appConfigEntity->getConfig(), true),
            true,
        );

        if ($transfer instanceof AppConfigTransfer) {
            $transfer->setConfig($decodedAppConfig);
        }

        return $transfer;
    }
}
