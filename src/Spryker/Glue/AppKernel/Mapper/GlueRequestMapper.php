<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AppKernel\Mapper;

use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\ConfigurationValidationRequestTransfer;
use Generated\Shared\Transfer\ConfigurationValidationResponseTransfer;
use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\AppKernel\AppKernelConfig;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class GlueRequestMapper implements GlueRequestMapperInterface
{
    public function __construct(protected UtilEncodingServiceInterface $utilEncodingService)
    {
    }

    public function mapGlueRequestTransferToAppConfigTransfer(
        GlueRequestTransfer $glueRequestTransfer,
        AppConfigTransfer $appConfigTransfer
    ): AppConfigTransfer {
        $tenantIdentifier = $this->getTenantIdentifier($glueRequestTransfer);
        $configuration = $this->getConfiguration($glueRequestTransfer);

        $appConfigTransfer->setConfig($configuration)
            ->setTenantIdentifier($tenantIdentifier);

        if ($glueRequestTransfer->getLocale() === null) {
            $glueRequestTransfer->setLocale('en_US');
        }

        $appConfigTransfer->setLocale($glueRequestTransfer->getLocale());

        return $appConfigTransfer;
    }

    public function mapGlueRequestTransferToConfigurationValidationRequestTransfer(
        GlueRequestTransfer $glueRequestTransfer
    ): ConfigurationValidationRequestTransfer {
        $configurationValidationRequestTransfer = new ConfigurationValidationRequestTransfer();
        $configurationValidationRequestTransfer->setAppConfig($this->mapGlueRequestTransferToAppConfigTransfer($glueRequestTransfer, new AppConfigTransfer()));

        return $configurationValidationRequestTransfer;
    }

    public function mapConfigurationValidationResponseTransferToGlueRequestValidationTransfer(
        ConfigurationValidationResponseTransfer $configurationValidationResponseTransfer
    ): GlueRequestValidationTransfer {
        $glueRequestValidationTransfer = new GlueRequestValidationTransfer();
        $glueRequestValidationTransfer->setIsValid($configurationValidationResponseTransfer->getIsSuccessful())
            ->setStatus($configurationValidationResponseTransfer->getIsSuccessful() === false ? Response::HTTP_UNPROCESSABLE_ENTITY : null);

        if ($configurationValidationResponseTransfer->getIsSuccessful() === false) {
            $glueErrorTransfer = new GlueErrorTransfer();
            $glueErrorTransfer->setMessage($configurationValidationResponseTransfer->getMessage() ?? $configurationValidationResponseTransfer->getExceptionMessage());

            $glueRequestValidationTransfer->addError($glueErrorTransfer);
            $glueRequestValidationTransfer->setStatus($configurationValidationResponseTransfer->getExceptionMessage() !== null && $configurationValidationResponseTransfer->getExceptionMessage() !== '' && $configurationValidationResponseTransfer->getExceptionMessage() !== '0' ? Response::HTTP_INTERNAL_SERVER_ERROR : Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $glueRequestValidationTransfer;
    }

    protected function getTenantIdentifier(GlueRequestTransfer $glueRequestTransfer): string
    {
        return $glueRequestTransfer->getMeta()[AppKernelConfig::HEADER_TENANT_IDENTIFIER][0] ?? '';
    }

    /**
     * @return array<string, string>
     */
    protected function getConfiguration(GlueRequestTransfer $glueRequestTransfer): array
    {
        $content = (array)$this->utilEncodingService->decodeJson((string)$glueRequestTransfer->getContent(), true);

        if (!isset($content['data']['attributes']['configuration'])) {
            return [];
        }

        return (array)$this->utilEncodingService->decodeJson($content['data']['attributes']['configuration'], true);
    }
}
