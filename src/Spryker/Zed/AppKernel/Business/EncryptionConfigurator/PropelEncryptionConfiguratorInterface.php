<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Business\EncryptionConfigurator;

interface PropelEncryptionConfiguratorInterface
{
    /**
     * @param string $tenantIdentifier
     *
     * @return bool
     */
    public function configurePropelEncryption(string $tenantIdentifier): bool;
}
