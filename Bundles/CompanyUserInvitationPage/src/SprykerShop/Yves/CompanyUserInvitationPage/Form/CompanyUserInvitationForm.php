<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \SprykerShop\Yves\CompanyUserInvitationPage\CompanyUserInvitationPageFactory getFactory()
 */
class CompanyUserInvitationForm extends AbstractType
{
    public const FIELD_INVITATIONS_LIST = 'invitations_list';

    protected const VALIDATION_NOT_BLANK_MESSAGE = 'validation.not_blank';

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'companyUserInvitationForm';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_INVITATIONS_LIST, FileType::class, [
            'label' => 'company.user.invitation.file',
            'required' => true,
            'constraints' => [
                $this->createNotBlankConstraint(),
                new Callback([
                    'callback' => function ($uploadedFile, ExecutionContextInterface $context) {
                        if ($uploadedFile && !$this->getFactory()->createImportFileValidator()->isValidImportFile($uploadedFile)) {
                            $context->buildViolation('company.user.invitation.import.file.invalid')->addViolation();
                        }
                    },
                ]),
            ],
        ]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\NotBlank
     */
    protected function createNotBlankConstraint(): NotBlank
    {
        return new NotBlank(['message' => static::VALIDATION_NOT_BLANK_MESSAGE]);
    }
}
