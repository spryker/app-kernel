<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Business\SecretsManager;

use Generated\Shared\Transfer\SecretKeyTransfer;
use Generated\Shared\Transfer\SecretTagTransfer;
use Generated\Shared\Transfer\SecretTransfer;
use Spryker\Zed\AppKernel\Business\Exception\SecretNotCreatedException;
use Spryker\Zed\AppKernel\Dependency\Client\AppKernelToSecretsManagerClientInterface;
use Spryker\Zed\AppKernel\Dependency\Service\AppKernelToUtilTextServiceInterface;

class SecretsManager implements SecretsManagerInterface
{
    /**
     * @var string
     */
    protected const SECRET_KEY_PREFIX = 'tenant_key';

    /**
     * @var string
     */
    protected const SECRET_KEY_TAG_KEY = 'type';

    /**
     * @var string
     */
    protected const SECRET_KEY_TAG_VALUE = 'tenant_key';

    /**
     * @param \Spryker\Zed\AppKernel\Dependency\Client\AppKernelToSecretsManagerClientInterface $secretsManagerClient
     * @param \Spryker\Zed\AppKernel\Dependency\Service\AppKernelToUtilTextServiceInterface $utilTextService
     */
    public function __construct(
        protected AppKernelToSecretsManagerClientInterface $secretsManagerClient,
        protected AppKernelToUtilTextServiceInterface $utilTextService
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getPassphrase(string $tenantIdentifier): string
    {
        $secretKeyTransfer = (new SecretKeyTransfer())
            ->setPrefix(static::SECRET_KEY_PREFIX)
            ->setIdentifier($tenantIdentifier);

        $secretTransfer = (new SecretTransfer())
            ->setSecretKey($secretKeyTransfer);

        $secretTransfer = $this->secretsManagerClient->getSecret($secretTransfer);

        $passphrase = $secretTransfer->getValue();

        if (!$passphrase) {
            $secretTransfer = $this->createSecret($secretTransfer, $tenantIdentifier);
        }

        return $secretTransfer->getValueOrFail();
    }

    /**
     * @param \Generated\Shared\Transfer\SecretTransfer $secretTransfer
     * @param string $tenantIdentifier
     *
     * @throws \Spryker\Zed\AppKernel\Business\Exception\SecretNotCreatedException
     *
     * @return \Generated\Shared\Transfer\SecretTransfer
     */
    public function createSecret(SecretTransfer $secretTransfer, string $tenantIdentifier): SecretTransfer
    {
        $passphrase = $this->utilTextService->generateRandomString(128);

        $secretTagTransfer = (new SecretTagTransfer())
            ->setKey(static::SECRET_KEY_TAG_KEY)
            ->setValue(static::SECRET_KEY_TAG_VALUE);

        $secretTransfer->setValue($passphrase)
            ->addSecretTag($secretTagTransfer);

        $isSuccessful = $this->secretsManagerClient->createSecret($secretTransfer);

        if (!$isSuccessful) {
            throw new SecretNotCreatedException(
                sprintf('The secret passphrase was not created for the Tenant: %s', $tenantIdentifier),
            );
        }

        return $secretTransfer;
    }
}
