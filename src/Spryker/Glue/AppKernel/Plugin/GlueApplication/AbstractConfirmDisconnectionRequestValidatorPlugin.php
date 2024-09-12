<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AppKernel\Plugin\GlueApplication;

use Generated\Shared\Transfer\GlueErrorConfirmTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface;
use Spryker\Glue\Kernel\Backend\AbstractPlugin;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractConfirmDisconnectionRequestValidatorPlugin extends AbstractPlugin implements RequestValidatorPluginInterface
{
    /**
     * @var string
     */
    protected const HEADER_CONFIRMATION_STATUS = 'x-confirmation-status';

    public function validate(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer
    {
        $glueRequestValidationTransfer = $this->validateDisconnectionRequest($glueRequestTransfer);

        if ($glueRequestValidationTransfer->getIsValid()) {
            return $glueRequestValidationTransfer;
        }

        $glueRequestValidationTransfer->requireErrors();

        $confirmationStatusValue = $this->extractConfirmationStatusValue($glueRequestTransfer);

        if ($confirmationStatusValue !== null) {
            $isConfirmed = filter_var($confirmationStatusValue, FILTER_VALIDATE_BOOLEAN);

            return match ($isConfirmed) {
                true => $this->onConfirmationOk($glueRequestTransfer),
                false => $this->onConfirmationCancel($glueRequestTransfer),
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

    abstract protected function validateDisconnectionRequest(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer;

    abstract protected function getLabelOk(): string;

    abstract protected function getLabelCancel(): string;

    abstract protected function onConfirmationOk(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer;

    abstract protected function onConfirmationCancel(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer;

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
