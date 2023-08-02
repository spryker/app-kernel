<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\App\Mapper;

use Generated\Shared\Transfer\AppDisconnectTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
use Spryker\Glue\App\AppConfig;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;

class GlueRequestMapper implements GlueRequestMapperInterface
{
    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected UtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(UtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
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
        $storeReference = $this->getStoreReference($glueRequestTransfer);

        $configuration = $this->getConfiguration($glueRequestTransfer);

        return $appConfigTransfer->fromArray($configuration, true)->setStoreReference($storeReference);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\AppDisconnectTransfer $appDisconnectTransfer
     *
     * @return \Generated\Shared\Transfer\AppDisconnectTransfer
     */
    public function mapGlueRequestTransferToAppDisconnectTransfer(
        GlueRequestTransfer $glueRequestTransfer,
        AppDisconnectTransfer $appDisconnectTransfer
    ): AppDisconnectTransfer {
        return $appDisconnectTransfer->setStoreReference($this->getStoreReference($glueRequestTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return string
     */
    protected function getStoreReference(GlueRequestTransfer $glueRequestTransfer): string
    {
        return $glueRequestTransfer->getMeta()[AppConfig::HEADER_STORE_REFERENCE][0];
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return array<string, string>
     */
    protected function getConfiguration(GlueRequestTransfer $glueRequestTransfer): array
    {
        $content = $this->utilEncodingService->decodeJson($glueRequestTransfer->getContent(), true);

        return $this->utilEncodingService->decodeJson($content['data']['attributes']['configuration'], true);
    }
}
