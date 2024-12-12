<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AppKernel\Plugin\GlueApplication;

use Generated\Shared\Transfer\GlueErrorConfirmTransfer;
use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\AppKernel\AppKernelConfig;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface;
use Spryker\Glue\Kernel\Backend\AbstractPlugin;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Glue\AppKernel\AppKernelFactory getFactory()
 */
abstract class AbstractConfirmDisconnectionRequestValidatorPlugin extends AbstractPlugin implements RequestValidatorPluginInterface
{
    /**
     * @var string
     */
    protected const HEADER_CONFIRMATION_STATUS = 'x-confirmation-status';

    public function validate(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer
    {
        $tenantIdentifier = $this->findTenantIdentifier($glueRequestTransfer);

        if ($tenantIdentifier === null || $tenantIdentifier === '') {
            return $this->getFailedGlueRequestValidationTransfer(
                AppKernelConfig::ERROR_CODE_PAYMENT_DISCONNECTION_TENANT_IDENTIFIER_MISSING,
                $this->getFactory()->getTranslatorFacade()->trans('Tenant identifier is missing.'),
            );
        }

        $glueRequestValidationTransfer = $this->validateDisconnectionRequest($glueRequestTransfer, $tenantIdentifier);

        if ($glueRequestValidationTransfer->getIsValid()) {
            return $glueRequestValidationTransfer;
        }

        $glueRequestValidationTransfer->requireErrors();

        $confirmationStatusValue = $this->extractConfirmationStatusValue($glueRequestTransfer);

        if ($confirmationStatusValue !== null) {
            $isConfirmed = filter_var($confirmationStatusValue, FILTER_VALIDATE_BOOLEAN);

            return match ($isConfirmed) {
                true => $this->onConfirmationOk(),
                false => $this->onConfirmationCancel(),
            };
        }

        $glueRequestValidationTransfer->setStatus(Response::HTTP_CONFLICT)
            ->getErrors()[0]
                ->setStatus(Response::HTTP_CONFLICT)
                ->setConfirm(
                    (new GlueErrorConfirmTransfer())
                        ->setLabelOk($this->getLabelOk())
                        ->setLabelCancel($this->getLabelCancel()),
                );

        return $glueRequestValidationTransfer;
    }

    abstract protected function validateDisconnectionRequest(
        GlueRequestTransfer $glueRequestTransfer,
        string $tenantIdentifier
    ): GlueRequestValidationTransfer;

    abstract protected function getCancellationErrorCode(): string;

    abstract protected function getCancellationErrorMessage(): string;

    protected function onConfirmationOk(): GlueRequestValidationTransfer
    {
        return (new GlueRequestValidationTransfer())
            ->setIsValid(true);
    }

    protected function onConfirmationCancel(): GlueRequestValidationTransfer
    {
        return (new GlueRequestValidationTransfer())
            ->setIsValid(false)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->addError(
                (new GlueErrorTransfer())
                    ->setCode($this->getCancellationErrorCode())
                    ->setStatus(Response::HTTP_BAD_REQUEST)
                    ->setMessage($this->getCancellationErrorMessage()),
            );
    }

    protected function getLabelOk(): string
    {
        return $this->getFactory()->getTranslatorFacade()->trans('Ignore & Disconnect');
    }

    protected function getLabelCancel(): string
    {
        return $this->getFactory()->getTranslatorFacade()->trans('Cancel');
    }

    protected function findTenantIdentifier(GlueRequestTransfer $glueRequestTransfer): ?string
    {
        return $glueRequestTransfer->getMeta()[AppKernelConfig::HEADER_TENANT_IDENTIFIER][0] ?? null;
    }

    protected function getFailedGlueRequestValidationTransfer(
        string $errorCode,
        string $errorMessage,
        ?int $httpStatus = Response::HTTP_BAD_REQUEST
    ): GlueRequestValidationTransfer {
        return (new GlueRequestValidationTransfer())
            ->setIsValid(false)
            ->setStatus($httpStatus)
            ->addError(
                (new GlueErrorTransfer())
                    ->setCode($errorCode)
                    ->setStatus($httpStatus)
                    ->setMessage($errorMessage),
            );
    }

    protected function extractConfirmationStatusValue(GlueRequestTransfer $glueRequestTransfer): ?string
    {
        $confirmationStatusMetaDatum = $glueRequestTransfer->getMeta()[static::HEADER_CONFIRMATION_STATUS] ?? [];

        if (!$confirmationStatusMetaDatum) {
            return null;
        }

        $confirmationStatusValue = $confirmationStatusMetaDatum[0] ?? '';

        if (!$confirmationStatusValue) {
            return null;
        }

        $confirmationStatusValues = explode(',', $confirmationStatusValue);
        $confirmationStatusExtractedValue = array_shift($confirmationStatusValues);
        $confirmationStatusMetaDatum[0] = implode(',', $confirmationStatusValues);
        $glueRequestTransfer->addMeta(static::HEADER_CONFIRMATION_STATUS, $confirmationStatusMetaDatum);

        return $confirmationStatusExtractedValue;
    }
}
