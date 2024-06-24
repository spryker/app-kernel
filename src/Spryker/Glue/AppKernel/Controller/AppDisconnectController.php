<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AppKernel\Controller;

use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\AppKernel\AppKernelConfig;
use Spryker\Glue\Kernel\Backend\Controller\AbstractController;
use Spryker\Zed\AppKernel\Persistence\Exception\AppConfigNotFoundException;

/**
 * @method \Spryker\Glue\AppKernel\AppKernelFactory getFactory()
 */
class AppDisconnectController extends AbstractController
{
    public function postDisconnectAction(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $glueRequestValidationTransfer = $this->getFactory()->createApiRequestDisconnectValidator()
            ->validate($glueRequestTransfer);

        if (!$glueRequestValidationTransfer->getIsValid()) {
            return $this->getFactory()->createResponseBuilder()
                ->buildRequestNotValidResponse($glueRequestValidationTransfer);
        }

        $appConfigTransfer = $this->getFactory()->createGlueRequestMapper()
            ->mapGlueRequestTransferToAppConfigTransfer($glueRequestTransfer, new AppConfigTransfer());

        $appConfigCriteriaTransfer = new AppConfigCriteriaTransfer();
        $appConfigCriteriaTransfer->setTenantIdentifier($appConfigTransfer->getTenantIdentifier());

        try {
            $appConfigTransfer = $this->getFactory()
                ->getAppKernelFacade()
                ->getConfig($appConfigCriteriaTransfer);

            $appConfigTransfer->setIsActive(false);
            $appConfigResponseTransfer = $this->getFactory()
                ->getAppKernelFacade()
                ->saveConfig($appConfigTransfer);
        } catch (AppConfigNotFoundException $appConfigNotFoundException) {
            return $this->getFactory()
            ->createResponseBuilder()
            ->buildErrorResponse($appConfigNotFoundException->getMessage());
        }

        if (!$appConfigResponseTransfer->getIsSuccessful()) {
            return $this->getFactory()
             ->createResponseBuilder()
             ->buildErrorResponse(AppKernelConfig::RESPONSE_MESSAGE_DISCONNECT_ERROR);
        }

        return $this->getFactory()->createResponseBuilder()->buildSuccessfulResponse();
    }
}
