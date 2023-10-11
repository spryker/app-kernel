<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Dependency\Client;

use Generated\Shared\Transfer\SecretTransfer;
use Spryker\Client\SecretsManager\SecretsManagerClientInterface;

class AppKernelToSecretsManagerClientBridge implements AppKernelToSecretsManagerClientInterface
{
    /**
     * @var \Spryker\Client\SecretsManager\SecretsManagerClientInterface
     */
    protected SecretsManagerClientInterface $secretsManagerClient;

    /**
     * @param \Spryker\Client\SecretsManager\SecretsManagerClientInterface $secretsManagerClient
     */
    public function __construct($secretsManagerClient)
    {
        $this->secretsManagerClient = $secretsManagerClient;
    }

    /**
     * @param \Generated\Shared\Transfer\SecretTransfer $secretTransfer
     *
     * @return \Generated\Shared\Transfer\SecretTransfer
     */
    public function getSecret(SecretTransfer $secretTransfer): SecretTransfer
    {
        return $this->secretsManagerClient->getSecret($secretTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SecretTransfer $secretTransfer
     *
     * @return bool
     */
    public function createSecret(SecretTransfer $secretTransfer): bool
    {
        return $this->secretsManagerClient->createSecret($secretTransfer);
    }
}
