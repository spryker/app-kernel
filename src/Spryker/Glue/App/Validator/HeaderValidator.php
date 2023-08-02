<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\App\Validator;

use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\App\AppConfig;
use Spryker\Zed\Translator\Business\TranslatorFacadeInterface;
use Symfony\Component\HttpFoundation\Response;

class HeaderValidator implements RequestValidatorInterface
{
    /**
     * @var \Spryker\Zed\Translator\Business\TranslatorFacadeInterface
     */
    protected TranslatorFacadeInterface $translatorFacade;

    /**
     * @param \Spryker\Zed\Translator\Business\TranslatorFacadeInterface $translatorFacade
     */
    public function __construct(TranslatorFacadeInterface $translatorFacade)
    {
        $this->translatorFacade = $translatorFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validate(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer
    {
        $glueRequestValidationTransfer = (new GlueRequestValidationTransfer())
            ->setIsValid(true)->setStatus(Response::HTTP_OK);

        $meta = $glueRequestTransfer->getMeta();

        if (empty($meta[AppConfig::HEADER_STORE_REFERENCE][0])) {
            $glueRequestValidationTransfer
                ->setIsValid(false)
                ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->addError(
                    (new GlueErrorTransfer())
                        ->setCode((string)Response::HTTP_UNPROCESSABLE_ENTITY)
                        ->setMessage($this->translatorFacade->trans(AppConfig::RESPONSE_MESSAGE_MISSING_STORE_REFERENCE)),
                );
        }

        return $glueRequestValidationTransfer;
    }
}
