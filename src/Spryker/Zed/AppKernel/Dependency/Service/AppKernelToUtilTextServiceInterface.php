<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Dependency\Service;

interface AppKernelToUtilTextServiceInterface
{
    /**
     * @param int $length
     */
    public function generateRandomString($length): string;
}
