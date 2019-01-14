<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AvailabilityUnsubscribeForm extends AbstractType
{
    public const FIELD_EMAIL = 'email';
    public const FIELD_SKU = 'sku';

    public const FORM_ID = 'availability_unsubscribe';

    protected const VALIDATION_NOT_BLANK_MESSAGE = 'validation.not_blank';
    protected const VALIDATION_EMAIL_MESSAGE = 'validation.email';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'availabilityNotificationUnsubscribeForm';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'attr' => [
                'id' => static::FORM_ID,
            ],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setAction('#' . static::FORM_ID);

        $this->addEmailField($builder);
        $this->addSkuField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addEmailField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_EMAIL, HiddenType::class, [
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new Email(),
                new Length(['min' => 1, 'max' => 255]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSkuField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_SKU, HiddenType::class, [
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new Length(['min' => 1, 'max' => 255]),
            ],
        ]);

        return $this;
    }
}
