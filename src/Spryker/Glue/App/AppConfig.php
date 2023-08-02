<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\App;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class AppConfig extends AbstractBundleConfig
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
    public const HEADER_STORE_REFERENCE = 'x-store-reference';

    /**
     * @var string
     */
    public const RESPONSE_MESSAGE_MISSING_STORE_REFERENCE = 'X-Store-Reference in header is required.';

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
