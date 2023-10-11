<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Persistence;

use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface AppKernelRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\AppConfigCriteriaTransfer $appConfigCriteriaTransfer
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function findAppConfigByCriteria(
        AppConfigCriteriaTransfer $appConfigCriteriaTransfer,
        TransferInterface $transfer
    ): TransferInterface;
}
