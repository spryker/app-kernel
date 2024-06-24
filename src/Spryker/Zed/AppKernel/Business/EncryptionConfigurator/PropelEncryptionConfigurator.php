<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Business\EncryptionConfigurator;

use Spryker\PropelEncryptionBehavior\Cipher;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\AppKernel\Business\SecretsManager\SecretsManagerInterface;

class PropelEncryptionConfigurator implements PropelEncryptionConfiguratorInterface
{
    use LoggerTrait;

    public function __construct(protected SecretsManagerInterface $secretsManager)
    {
    }

    public function configurePropelEncryption(string $tenantIdentifier): bool
    {
        $passphrase = $this->secretsManager->getPassphrase($tenantIdentifier);

        Cipher::resetInstance();
        Cipher::createInstance($passphrase);

        return true;
    }
}
