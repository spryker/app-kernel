<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AppKernel;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class AppKernelConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const CONFIGURE_ROUTE_PATH = '/private/configure';

    /**
     * @var string
     */
    public const DISCONNECT_ROUTE_PATH = '/private/disconnect';

    /**
     * @var string
     */
    public const HEADER_TENANT_IDENTIFIER = 'x-tenant-identifier';

    /**
     * @var string
     */
    public const RESPONSE_MESSAGE_MISSING_TENANT_IDENTIFIER = 'X-Tenant-Identifier in header is required.';

    /**
     * @var string
     */
    public const REQUEST_DATA_TYPE = 'configuration';

    /**
     * @var string
     */
    public const RESPONSE_MESSAGE_VALIDATION_FORMAT_ERROR_MESSAGE = 'Wrong request format.';

    /**
     * @var string
     */
    public const RESPONSE_MESSAGE_CONFIGURE_ERROR = 'Configuration error.';

    /**
     * @var string
     */
    public const RESPONSE_MESSAGE_DISCONNECT_ERROR = 'Disconnecting error.';

    /**
     * @var string
     */
    public const RESPONSE_MESSAGE_BLANK_CLIENT_ID_FIELD = 'Client ID is required.';

    /**
     * @var string
     */
    public const RESPONSE_MESSAGE_NOT_STRING_CLIENT_ID_FIELD = 'Client ID must be a string.';

    /**
     * @var string
     */
    public const RESPONSE_MESSAGE_BLANK_CLIENT_SECRET_FIELD = 'Client Secret is required.';

    /**
     * @var string
     */
    public const RESPONSE_MESSAGE_NOT_STRING_CLIENT_SECRET_FIELD = 'Client Secret must be a string.';
}
