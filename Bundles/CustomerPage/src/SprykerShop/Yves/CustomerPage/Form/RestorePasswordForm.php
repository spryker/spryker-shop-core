<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageConfig getConfig()
 */
class RestorePasswordForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_RESTORE_PASSWORD_KEY = 'restore_password_key';

    /**
     * @var string
     */
    public const FIELD_PASSWORD = 'password';

    /**
     * @var string
     */
    protected const VALIDATION_MIN_LENGTH_MESSAGE = 'validation.min_length';

    /**
     * @var string
     */
    protected const VALIDATION_MAX_LENGTH_MESSAGE = 'validation.max_length.singular';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'restoreForm';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addRestorePasswordKeyField($builder)
            ->addPasswordField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addRestorePasswordKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_RESTORE_PASSWORD_KEY, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPasswordField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PASSWORD, RepeatedType::class, [
            'first_name' => 'pass',
            'second_name' => 'confirm',
            'type' => PasswordType::class,
            'invalid_message' => 'validator.constraints.password.do_not_match',
            'required' => true,
            'first_options' => [
                'label' => 'forms.password',
                'attr' => [
                    'password_complexity_indicator' => true,
                ],
            ],
            'second_options' => [
                'label' => 'forms.confirm-password',
            ],
            'constraints' => [
                new Length([
                    'min' => $this->getConfig()->getCustomerPasswordMinLength(),
                    'max' => $this->getConfig()->getCustomerPasswordMaxLength(),
                    'minMessage' => static::VALIDATION_MIN_LENGTH_MESSAGE,
                    'maxMessage' => static::VALIDATION_MAX_LENGTH_MESSAGE,
                ]),
                $this->createNotBlankConstraint(),
            ],
        ]);

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\NotBlank
     */
    protected function createNotBlankConstraint(): NotBlank
    {
        return new NotBlank();
    }
}
