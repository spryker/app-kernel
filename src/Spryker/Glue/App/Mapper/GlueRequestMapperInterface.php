<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\App\Mapper;

use Generated\Shared\Transfer\AppDisconnectTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;

interface GlueRequestMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AppConfigTransfer
     */
    public function mapGlueRequestTransferToAppConfigTransfer(
        GlueRequestTransfer $glueRequestTransfer,
        AppConfigTransfer $appConfigTransfer
    ): AppConfigTransfer;

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\AppDisconnectTransfer $appDisconnectTransfer
     *
     * @return \Generated\Shared\Transfer\AppDisconnectTransfer
     */
    public function mapGlueRequestTransferToAppDisconnectTransfer(
        GlueRequestTransfer $glueRequestTransfer,
        AppDisconnectTransfer $appDisconnectTransfer
    ): AppDisconnectTransfer;
}
