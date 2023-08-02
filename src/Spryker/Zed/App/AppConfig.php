<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\App;

use Spryker\Shared\App\AppConstants;
use Spryker\Shared\GlueBackendApiApplication\GlueBackendApiApplicationConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class AppConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getAppIdentifier(): string
    {
        return $this->get(AppConstants::APP_IDENTIFIER);
    }

    /**
     * @return string
     */
    public function getAppUrl(): string
    {
        return $this->get(GlueBackendApiApplicationConstants::GLUE_BACKEND_API_HOST);
    }
}
