<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\AppKernel;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class AppKernelConstants
{
    /**
     * Specification:
     * - Identifier of the application.
     *
     * @api
     *
     * @var string
     */
    public const APP_IDENTIFIER = 'APP:APP_IDENTIFIER';

    /**
     * Specification:
     * - Identifier of the application.
     *
     * @api
     *
     * @var string
     */
    public const APP_VERSION = 'APP:APP_VERSION';

    /**
     * Specification:
     * - Path to an OpenAPI schema path that can be used for request validation.
     *
     * @api
     *
     * @var string
     */
    public const OPEN_API_SCHEMA_PATH = 'APP:OPEN_API_SCHEMA_PATH';
}
