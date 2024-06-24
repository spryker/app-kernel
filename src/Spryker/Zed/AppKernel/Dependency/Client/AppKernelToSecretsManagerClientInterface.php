<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Dependency\Client;

use Generated\Shared\Transfer\SecretTransfer;

interface AppKernelToSecretsManagerClientInterface
{
    public function getSecret(SecretTransfer $secretTransfer): SecretTransfer;

    public function createSecret(SecretTransfer $secretTransfer): bool;
}
