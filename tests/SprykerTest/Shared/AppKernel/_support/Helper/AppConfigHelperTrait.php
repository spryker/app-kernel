<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\AppKernel\Helper;

use Codeception\Module;

trait AppConfigHelperTrait
{
    protected function getAppConfigHelper(): AppConfigHelper
    {
        /** @var \SprykerTest\Shared\AppKernel\Helper\AppConfigHelper $appConfigHelper */
        $appConfigHelper = $this->getModule('\\' . AppConfigHelper::class);

        return $appConfigHelper;
    }

    abstract protected function getModule(string $name): Module;
}
