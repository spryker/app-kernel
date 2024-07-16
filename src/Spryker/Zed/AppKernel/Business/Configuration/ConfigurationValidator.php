<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Business\Configuration;

use Generated\Shared\Transfer\AppConfigValidateResponseTransfer;
use Generated\Shared\Transfer\ConfigurationValidationRequestTransfer;
use Generated\Shared\Transfer\ConfigurationValidationResponseTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\AppKernel\Dependency\Service\AppKernelToUtilEncodingServiceInterface;
use Spryker\Zed\AppKernelExtension\Dependency\Plugin\AppKernelPlatformPluginInterface;
use Throwable;

class ConfigurationValidator
{
    use LoggerTrait;

    public function __construct(
        protected AppKernelPlatformPluginInterface $appKernelPlatformPlugin,
        protected AppKernelToUtilEncodingServiceInterface $appKernelToUtilEncodingService
    ) {
    }

    public function validateConfiguration(
        ConfigurationValidationRequestTransfer $configurationValidationRequestTransfer
    ): ConfigurationValidationResponseTransfer {
        $configurationValidationResponseTransfer = (new ConfigurationValidationResponseTransfer())
            ->setIsSuccessful(true);

        try {
            $appConfigValidateResponseTransfer = $this->appKernelPlatformPlugin->validateConfiguration($configurationValidationRequestTransfer->getAppConfigOrFail());
        } catch (Throwable $throwable) {
            return $this->buildFailedResponseFromException($throwable, $configurationValidationResponseTransfer);
        }

        if ($appConfigValidateResponseTransfer->getIsSuccessful() === true) {
            return $configurationValidationResponseTransfer;
        }

        return $this->mapAppConfigurationValidationResponseTransferToConfigurationValidationResponseTransfer($appConfigValidateResponseTransfer, $configurationValidationResponseTransfer);
    }

    protected function mapAppConfigurationValidationResponseTransferToConfigurationValidationResponseTransfer(
        AppConfigValidateResponseTransfer $appConfigValidateResponseTransfer,
        ConfigurationValidationResponseTransfer $configurationValidationResponseTransfer
    ): ConfigurationValidationResponseTransfer {
        $configurationValidationResponseTransfer
            ->setIsSuccessful(false);

        $messages = [];
        foreach ($appConfigValidateResponseTransfer->getConfigurationValidationErrors() as $configurationValidationErrorTransfer) {
            foreach ($configurationValidationErrorTransfer->getErrorMessages() as $errorMessage) {
                $messages[$configurationValidationErrorTransfer->getProperty()][] = $errorMessage;
            }

            $messages[$configurationValidationErrorTransfer->getProperty()] = implode(', ', $messages[$configurationValidationErrorTransfer->getProperty()]);
        }

        $configurationValidationResponseTransfer->setMessage(implode(', ', $messages));

        return $configurationValidationResponseTransfer;
    }

    protected function buildFailedResponseFromException(
        Throwable $throwable,
        ConfigurationValidationResponseTransfer $configurationValidationResponseTransfer
    ): ConfigurationValidationResponseTransfer {
        $this->getLogger()->error($throwable->getMessage());
        $configurationValidationResponseTransfer
            ->setIsSuccessful(false)
            ->setExceptionMessage($throwable->getMessage());

        return $configurationValidationResponseTransfer;
    }
}
