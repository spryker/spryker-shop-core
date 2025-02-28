<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Form;

use Spryker\Shared\Validator\Constraints\NotCompromisedPassword;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageConfig getConfig()
 */
class PasswordForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_NEW_PASSWORD = 'new_password';

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
        return 'passwordForm';
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
            ->addPasswordField($builder)
            ->addNewPasswordField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addNewPasswordField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_NEW_PASSWORD, RepeatedType::class, [
            'first_name' => static::FIELD_PASSWORD,
            'second_name' => 'confirm',
            'type' => PasswordType::class,
            'invalid_message' => 'validator.constraints.password.do_not_match',
            'required' => true,
            'first_options' => [
                'label' => 'customer.password.request.new_password',
                'attr' => [
                    'autocomplete' => 'off',
                    'password_complexity_indicator' => true,
                ],
            ],
            'second_options' => [
                'label' => 'customer.password.confirm.new_password',
                'attr' => ['autocomplete' => 'off'],
            ],
            'constraints' => [
                new NotBlank(),
                new Length([
                    'min' => $this->getConfig()->getCustomerPasswordMinLength(),
                    'max' => $this->getConfig()->getCustomerPasswordMaxLength(),
                    'minMessage' => static::VALIDATION_MIN_LENGTH_MESSAGE,
                    'maxMessage' => static::VALIDATION_MAX_LENGTH_MESSAGE,
                ]),
                new Regex([
                    'pattern' => $this->getConfig()->getCustomerPasswordPattern(),
                    'message' => $this->getConfig()->getPasswordValidationMessage(),
                ]),
                new NotCompromisedPassword(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPasswordField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PASSWORD, PasswordType::class, [
            'label' => 'customer.password.old_password',
            'required' => true,
            'attr' => [
                'autocomplete' => 'off',
            ],
        ]);

        return $this;
    }
}
