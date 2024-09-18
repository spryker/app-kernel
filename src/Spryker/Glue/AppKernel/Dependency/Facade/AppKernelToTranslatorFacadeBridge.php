<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AppKernel\Dependency\Facade;

use Spryker\Zed\Translator\Business\TranslatorFacadeInterface;

class AppKernelToTranslatorFacadeBridge implements AppKernelToTranslatorFacadeInterface
{
    public function __construct(protected TranslatorFacadeInterface $translatorFacade)
    {
    }

    /**
     * @param array<mixed> $parameters
     * @param string|null $domain
     * @param string|null $locale
     */
    public function trans(string $id, array $parameters = [], ?string $domain = null, ?string $locale = null): string
    {
        return $this->translatorFacade->trans($id, $parameters, $domain, $locale);
    }
}
