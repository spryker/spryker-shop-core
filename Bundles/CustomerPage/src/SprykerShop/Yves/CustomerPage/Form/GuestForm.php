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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class GuestForm extends AbstractType
{
    const FIELD_SALUTATION = 'salutation';
    const FIELD_FIRST_NAME = 'first_name';
    const FIELD_LAST_NAME = 'last_name';
    const FIELD_EMAIL = 'email';
    const FIELD_IS_GUEST = 'is_guest';
    const FIELD_ACCEPT_TERMS = 'accept_terms';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'guestForm';
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
            ->addIsGuestField($builder)
            ->addAcceptTermsField($builder);
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
            'choices_as_values' => true,
            'label' => 'address.salutation',
            'constraints' => [
                new NotBlank(),
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
            'constraints' => [
                new NotBlank(),
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
            'constraints' => [
                new NotBlank(),
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
            'constraints' => [
                new NotBlank(),
                new Callback([
                    'callback' => function ($email, ExecutionContextInterface $context) {
                        $isEmailFormatValid = $this->getFactory()
                            ->getUtilValidateService()
                            ->isEmailFormatValid($email);

                        if (!$isEmailFormatValid) {
                            $context->buildViolation('customer.email.format.invalid')->addViolation();
                        }
                    },
                ]),
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
            'data' => true,
        ]);

        $this->addIsGuestTransformer($builder);

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
            'constraints' => [
                new NotBlank(),
            ],
        ]);

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
}
