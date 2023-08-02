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
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Zed\Translator\Business\TranslatorFacadeInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BodyStructureValidator implements RequestValidatorInterface
{
    /**
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    protected ValidatorInterface $validator;

    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected UtilEncodingServiceInterface $utilEncodingService;

    /**
     * @var \Spryker\Zed\Translator\Business\TranslatorFacadeInterface
     */
    protected TranslatorFacadeInterface $translatorFacade;

    /**
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface $validator
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\Translator\Business\TranslatorFacadeInterface $translatorFacade
     */
    public function __construct(
        ValidatorInterface $validator,
        UtilEncodingServiceInterface $utilEncodingService,
        TranslatorFacadeInterface $translatorFacade
    ) {
        $this->validator = $validator;
        $this->utilEncodingService = $utilEncodingService;
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
            ->setIsValid(true)
            ->setStatus(Response::HTTP_OK);

        $content = $this->utilEncodingService->decodeJson($glueRequestTransfer->getContent(), true);
        $constraintViolationList = $this->validator->validate($content, $this->getConstraintForRequestStructure());

        if ($constraintViolationList->count() > 0) {
            $glueRequestValidationTransfer
                ->setIsValid(false)
                ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

            $glueRequestValidationTransfer->addError(
                (new GlueErrorTransfer())
                    ->setCode((string)Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->setMessage($this->translatorFacade->trans(AppConfig::RESPONSE_MESSAGE_VALIDATION_FORMAT_ERROR_MESSAGE)),
            );
        }

        return $glueRequestValidationTransfer;
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\Collection
     */
    protected function getConstraintForRequestStructure(): Collection
    {
        return new Collection([
            'data' => new Collection([
                'type' => new EqualTo(AppConfig::REQUEST_DATA_TYPE),
                'attributes' => new Collection([
                    'configuration' => [
                        new Required(),
                        new NotBlank(),
                        new Type(['type' => 'string']),
                        new Json(),
                    ],
                ]),
            ]),
        ]);
    }
}
