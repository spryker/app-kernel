<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AppKernel\Controller;

use Generated\Shared\Transfer\AppDisconnectTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\AppKernel\AppKernelConfig;
use Spryker\Glue\Kernel\Backend\Controller\AbstractController;

/**
 * @method \Spryker\Glue\AppKernel\AppKernelFactory getFactory()
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

        $appDisconnectResponseTransfer = $this->getFactory()->getAppKernelFacade()
            ->deleteConfig($disconnectParameterTransfer);

        if (!$appDisconnectResponseTransfer->getIsSuccessful()) {
            return $this->getFactory()->createResponseBuilder()
                ->buildErrorResponse(AppKernelConfig::RESPONSE_MESSAGE_DISCONNECT_ERROR);
        }

        return $this->getFactory()->createResponseBuilder()->buildSuccessfulResponse();
    }
}
