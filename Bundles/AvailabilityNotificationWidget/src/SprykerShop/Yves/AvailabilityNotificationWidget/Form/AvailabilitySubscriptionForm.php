<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class AvailabilitySubscriptionForm extends AbstractType
{
    public const FIELD_EMAIL = 'email';
    public const FORM_ID = 'availability_subscription';

    protected const VALIDATION_NOT_BLANK_MESSAGE = 'validation.not_blank';
    protected const VALIDATION_EMAIL_MESSAGE = 'validation.email';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'availabilityNotificationSubscriptionForm';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [
                'id' => self::FORM_ID,
            ],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAction('#' . self::FORM_ID);

        $this->addEmailField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addEmailField(FormBuilderInterface $builder): self
    {
        $builder->add(self::FIELD_EMAIL, EmailType::class, [
            'label' => 'customer.availability_notification.subscribe',
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new Email(),
            ],
        ]);

        return $this;
    }
}
