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
class AppDisconnectController extends AbstractController
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function postDisconnectAction(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $glueRequestValidationTransfer = $this->getFactory()->createApiRequestDisconnectValidator()
            ->validate($glueRequestTransfer);

        if (!$glueRequestValidationTransfer->getIsValid()) {
            return $this->getFactory()->createResponseBuilder()
                ->buildRequestNotValidResponse($glueRequestValidationTransfer);
        }

        $disconnectParameterTransfer = $this->getFactory()->createGlueRequestMapper()
            ->mapGlueRequestTransferToAppDisconnectTransfer($glueRequestTransfer, new AppDisconnectTransfer());

        $appDisconnectResponseTransfer = $this->getFactory()->getAppFacade()
            ->deleteConfig($disconnectParameterTransfer);

        if (!$appDisconnectResponseTransfer->getIsSuccessful()) {
            return $this->getFactory()->createResponseBuilder()
                ->buildErrorResponse(
                    $this->getFactory()->getTranslatorFacade()
                        ->trans(AppConfig::RESPONSE_MESSAGE_DISCONNECT_ERROR),
                );
        }

        return $this->getFactory()->createResponseBuilder()->buildSuccessfulResponse();
    }
}
