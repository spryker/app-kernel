<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AppKernel\Mapper;

use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\AppDisconnectTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\AppKernel\AppKernelConfig;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;

class GlueRequestMapper implements GlueRequestMapperInterface
{
    /**
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(protected UtilEncodingServiceInterface $utilEncodingService)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AppConfigTransfer
     */
    public function mapGlueRequestTransferToAppConfigTransfer(
        GlueRequestTransfer $glueRequestTransfer,
        AppConfigTransfer $appConfigTransfer
    ): AppConfigTransfer {
        $tenantIdentifier = $this->getTenantIdentifier($glueRequestTransfer);
        $configuration = $this->getConfiguration($glueRequestTransfer);

        $appConfigTransfer->setConfig($configuration)
            ->setTenantIdentifier($tenantIdentifier);

        return $appConfigTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return string
     */
    protected function getTenantIdentifier(GlueRequestTransfer $glueRequestTransfer): string
    {
        return $glueRequestTransfer->getMeta()[AppKernelConfig::HEADER_TENANT_IDENTIFIER][0];
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
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
