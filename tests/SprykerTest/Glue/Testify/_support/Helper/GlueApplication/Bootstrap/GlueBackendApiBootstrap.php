<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Testify\Helper\GlueApplication\Bootstrap;

use Spryker\Glue\GlueApplication\Bootstrap\GlueBootstrap;
use Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication\BackendApiGlueApplicationBootstrapPlugin;
use Spryker\Shared\Application\ApplicationInterface;

class GlueBackendApiBootstrap extends GlueBootstrap
{
    /**
     * @param array<string> $glueApplicationBootstrapPluginClassNames
     */
    public function boot(array $glueApplicationBootstrapPluginClassNames = []): ApplicationInterface
    {
        return parent::boot([BackendApiGlueApplicationBootstrapPlugin::class]);
    }
}
