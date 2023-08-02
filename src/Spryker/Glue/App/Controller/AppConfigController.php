<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\App\Controller;

use Generated\Shared\Transfer\AppDisconnectTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
use Spryker\Glue\App\AppConfig;
use Spryker\Glue\Kernel\Backend\Controller\AbstractController;

/**
 * @method \Spryker\Glue\App\AppFactory getFactory()
 */
class AppConfigController extends AbstractController
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function postConfigureAction(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $glueRequestValidationTransfer = $this->getFactory()->createApiRequestSaveConfigValidator()
            ->validate($glueRequestTransfer);

        if (!$glueRequestValidationTransfer->getIsValid()) {
            return $this->getFactory()->createResponseBuilder()
                ->buildRequestNotValidResponse($glueRequestValidationTransfer);
        }

        $appConfigTransfer = $this->getFactory()->createGlueRequestMapper()
            ->mapGlueRequestTransferToAppConfigTransfer($glueRequestTransfer, new AppConfigTransfer());

        $appConfigResponseTransfer = $this->getFactory()->getAppFacade()
            ->saveConfig($appConfigTransfer);

        return $this->getFactory()->createResponseBuilder()->buildSuccessfulResponse(
            $appConfigResponseTransfer->getAppConfig(),
        );
    }
}
