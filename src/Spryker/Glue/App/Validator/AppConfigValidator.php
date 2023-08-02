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
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AppConfigValidator implements RequestValidatorInterface
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
            ->setIsValid(true)->setStatus(Response::HTTP_OK);

        $content = $this->utilEncodingService->decodeJson($glueRequestTransfer->getContent(), true);
        $configuration = $this->utilEncodingService->decodeJson($content['data']['attributes']['configuration'], true);
        $constraintViolationList = $this->validator->validate($configuration, $this->createConstraintForConfiguration());

        if ($constraintViolationList->count() > 0) {
            $glueRequestValidationTransfer
                ->setIsValid(false)
                ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

            /** @var \Symfony\Component\Validator\ConstraintViolationInterface $constraintViolation */
            foreach ($constraintViolationList as $constraintViolation) {
                $glueRequestValidationTransfer->addError(
                    (new GlueErrorTransfer())
                        ->setCode((string)Response::HTTP_UNPROCESSABLE_ENTITY)
                        ->setMessage($constraintViolation->getMessage()),
                );
            }
        }

        return $glueRequestValidationTransfer;
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\Collection
     */
    protected function createConstraintForConfiguration(): Collection
    {
        return new Collection([
            'clientId' => [
                new Required(),
                $this->createNotBlankConstraint(AppConfig::RESPONSE_MESSAGE_BLANK_CLIENT_ID_FIELD),
                $this->createTypeStringConstraint(AppConfig::RESPONSE_MESSAGE_NOT_STRING_CLIENT_ID_FIELD),
            ],
            'clientSecret' => [
                new Required(),
                $this->createNotBlankConstraint(AppConfig::RESPONSE_MESSAGE_BLANK_CLIENT_SECRET_FIELD),
                $this->createTypeStringConstraint(AppConfig::RESPONSE_MESSAGE_NOT_STRING_CLIENT_SECRET_FIELD),
            ],
            'isActive' => [
                new Required(),
            ],
        ], null, null, true);
    }

    /**
     * @param string $errorMessage
     *
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createNotBlankConstraint(string $errorMessage): Constraint
    {
        $notBlank = new NotBlank();
        $notBlank->message = $this->translatorFacade->trans($errorMessage);

        return $notBlank;
    }

    /**
     * @param string $errorMessage
     *
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createTypeStringConstraint(string $errorMessage): Constraint
    {
        $type = new Type(['type' => 'string']);
        $type->message = $this->translatorFacade->trans($errorMessage);

        return $type;
    }

    /**
     * @param string $errorMessage
     *
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createUrlConstraint(string $errorMessage): Constraint
    {
        $url = new Url();
        $url->message = $this->translatorFacade->trans($errorMessage);

        return $url;
    }
}
