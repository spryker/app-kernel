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
class AppConfigController extends AbstractController
{
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

        $appConfigTransfer = $this->updateStatus($appConfigTransfer);
        $appConfigTransfer->setIsActive(true);

        $appConfigResponseTransfer = $this->getFactory()->getAppKernelFacade()
            ->saveConfig($appConfigTransfer);

        if (!$appConfigResponseTransfer->getIsSuccessful()) {
            return $this->getFactory()->createResponseBuilder()
                ->buildErrorResponse($appConfigResponseTransfer->getErrorMessage() ?? AppKernelConfig::RESPONSE_MESSAGE_CONFIGURE_ERROR);
        }

        return $this->getFactory()->createResponseBuilder()->buildSuccessfulResponse(
            $appConfigResponseTransfer->getAppConfig(),
        );
    }

    protected function updateStatus(AppConfigTransfer $appConfigTransfer): AppConfigTransfer
    {
        if ($appConfigTransfer->getStatus() === null) {
            try {
                $existingAppConfigTransfer = $this->getFactory()->getAppKernelFacade()
                    ->getConfig((new AppConfigCriteriaTransfer())->setTenantIdentifier($appConfigTransfer->getTenantIdentifier()));

                $appConfigTransfer->setStatus($existingAppConfigTransfer->getStatus());
                $appConfigTransfer->setIsActive($existingAppConfigTransfer->getIsActive());
            } catch (AppConfigNotFoundException) {
            }
        }

        return $appConfigTransfer;
    }
}
