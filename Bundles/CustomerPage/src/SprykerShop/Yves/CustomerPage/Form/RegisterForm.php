<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageConfig getConfig()
 */
class RegisterForm extends AbstractType
{
    public const FIELD_SALUTATION = 'salutation';
    public const FIELD_FIRST_NAME = 'first_name';
    public const FIELD_LAST_NAME = 'last_name';
    public const FIELD_EMAIL = 'email';
    public const FIELD_PASSWORD = 'password';
    public const FIELD_ACCEPT_TERMS = 'accept_terms';
    public const FIELD_IS_GUEST = 'is_guest';

    public const BLOCK_PREFIX = 'registerForm';

    public const OPTION_MIN_LENGTH_CUSTOMER_PASSWORD = 'OPTION_MIN_LENGTH_CUSTOMER_PASSWORD';

    protected const VALIDATION_NOT_BLANK_MESSAGE = 'validation.not_blank';
    protected const VALIDATION_MIN_LENGTH_MESSAGE = 'validation.min_length';
    protected const VALIDATION_MAX_LENGTH_MESSAGE = 'validation.max_length.singular';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return static::BLOCK_PREFIX;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addSalutationField($builder)
            ->addFirstNameField($builder)
            ->addLastNameField($builder)
            ->addEmailField($builder)
            ->addPasswordField($builder, $options)
            ->addAcceptTermsField($builder)
            ->addIsGuestField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSalutationField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_SALUTATION, ChoiceType::class, [
            'choices' => array_flip([
                'Mr' => 'customer.salutation.mr',
                'Ms' => 'customer.salutation.ms',
                'Mrs' => 'customer.salutation.mrs',
                'Dr' => 'customer.salutation.dr',
            ]),
            'required' => true,
            'label' => 'address.salutation',
            'constraints' => [
                $this->createNotBlankConstraint(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFirstNameField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_FIRST_NAME, TextType::class, [
            'label' => 'customer.first_name',
            'required' => true,
            'constraints' => [
                $this->createNotBlankConstraint(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLastNameField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_LAST_NAME, TextType::class, [
            'label' => 'customer.last_name',
            'required' => true,
            'constraints' => [
                $this->createNotBlankConstraint(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addEmailField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_EMAIL, EmailType::class, [
            'label' => 'auth.email',
            'required' => true,
            'constraints' => [
                $this->createNotBlankConstraint(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addPasswordField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_PASSWORD, RepeatedType::class, [
            'first_name' => 'pass',
            'second_name' => 'confirm',
            'type' => PasswordType::class,
            'invalid_message' => 'validator.constraints.password.do_not_match',
            'required' => true,
            'first_options' => [
                'label' => 'forms.password',
                'attr' => ['autocomplete' => 'off'],
            ],
            'second_options' => [
                'label' => 'forms.confirm-password',
                'attr' => ['autocomplete' => 'off'],
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
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAcceptTermsField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ACCEPT_TERMS, CheckboxType::class, [
            'label' => 'forms.accept_terms',
            'mapped' => false,
            'required' => true,
            'constraints' => [
                $this->createNotBlankConstraint(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsGuestField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_IS_GUEST, HiddenType::class, [
            'data' => false,
        ]);

        $this->addIsGuestTransformer($builder);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addIsGuestTransformer(FormBuilderInterface $builder)
    {
        $builder->get(self::FIELD_IS_GUEST)->addModelTransformer(new CallbackTransformer(
            function ($isGuest) {
                return $isGuest;
            },
            function ($isGuestSubmittedValue) {
                return (bool)$isGuestSubmittedValue;
            }
        ));
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\NotBlank
     */
    protected function createNotBlankConstraint(): NotBlank
    {
        return new NotBlank(['message' => static::VALIDATION_NOT_BLANK_MESSAGE]);
    }
}
