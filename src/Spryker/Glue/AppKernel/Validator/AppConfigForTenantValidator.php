<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AppKernel\Validator;

use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\AppKernel\AppKernelConfig;
use Spryker\Glue\AppKernel\Dependency\Facade\AppKernelToAppKernelFacadeInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\AppKernel\AppKernelConfig as AppKernelAppKernelConfig;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AppConfigForTenantValidator
{
    use LoggerTrait;

    public function __construct(protected AppKernelToAppKernelFacadeInterface $appKernelToAppKernelFacade, protected AppKernelConfig $appKernelConfig)
    {
    }

    public function validate(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer
    {
        $glueRequestValidationTransfer = new GlueRequestValidationTransfer();
        $glueRequestValidationTransfer->setIsValid(true);

        if ($this->isPathExcludedFromValidation($glueRequestTransfer)) {
            return $glueRequestValidationTransfer;
        }

        $tenantIdentifier = $this->getTenantIdentifier($glueRequestTransfer);

        $appConfigCriteriaTransfer = new AppConfigCriteriaTransfer();
        $appConfigCriteriaTransfer->setTenantIdentifier($tenantIdentifier);

        try {
            $appConfigTransfer = $this->appKernelToAppKernelFacade->getConfig($appConfigCriteriaTransfer);
            if ($appConfigTransfer->getStatus() === AppKernelAppKernelConfig::APP_STATUS_DISCONNECTED) {
                $glueErrorTransfer = new GlueErrorTransfer();
                $glueErrorTransfer->setMessage('Tenant is disconnected.');

                $glueRequestValidationTransfer
                    ->setIsValid(false)
                    ->setStatus(Response::HTTP_FORBIDDEN)
                    ->addError($glueErrorTransfer);
            }
        } catch (Throwable $throwable) {
            $this->getLogger()->error(
                $throwable->getMessage(),
                $glueRequestTransfer->toArray(),
            );

            $glueErrorTransfer = new GlueErrorTransfer();
            $glueErrorTransfer
                ->setMessage($throwable->getMessage());

            $glueRequestValidationTransfer
                ->setIsValid(false)
                ->addError($glueErrorTransfer)
                ->setStatus(Response::HTTP_INTERNAL_SERVER_ERROR);

            return $glueRequestValidationTransfer;
        }

        return $glueRequestValidationTransfer;
    }

    protected function isPathExcludedFromValidation(GlueRequestTransfer $glueRequestTransfer): bool
    {
        return in_array($glueRequestTransfer->getPath(), $this->appKernelConfig->getValidationExcludedPaths());
    }

    protected function getTenantIdentifier(GlueRequestTransfer $glueRequestTransfer): ?string
    {
        $meta = $glueRequestTransfer->getMeta();

        if (!isset($meta['x-tenant-identifier'])) {
            return null;
        }

        $tenantIdentifier = $meta['x-tenant-identifier'];

        if (is_array($tenantIdentifier)) {
            return $tenantIdentifier[0];
        }

        return $tenantIdentifier;
    }
}
