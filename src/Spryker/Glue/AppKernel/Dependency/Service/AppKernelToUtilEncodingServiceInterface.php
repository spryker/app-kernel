<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AppKernel\Dependency\Service;

interface AppKernelToUtilEncodingServiceInterface
{
    /**
     * @param string $jsonValue
     * @param bool $assoc Deprecated: `false` is deprecated, always use `true` for array return.
     * @param int|null $depth
     * @param int|null $options
     *
     * @return object|array<mixed>|null
     */
    public function decodeJson($jsonValue, $assoc = false, $depth = null, $options = null): object|array|null;

    /**
     * @param array<string, mixed> $value
     * @param int|null $options
     * @param int|null $depth
     */
    public function encodeJson($value, $options = null, $depth = null): string|null;
}
