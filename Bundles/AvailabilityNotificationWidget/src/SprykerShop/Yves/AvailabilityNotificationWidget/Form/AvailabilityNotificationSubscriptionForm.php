<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationWidget\Form;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AvailabilityNotificationSubscriptionForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_EMAIL = 'email';

    /**
     * @var string
     */
    public const FIELD_SKU = 'sku';

    /**
     * @var string
     */
    public const FORM_ID = 'availability_notification_subscription';

    /**
     * @var string
     */
    public const PLACEHOLDER_EMAIL_ADDRESS = 'availability_notification.email_address';

    /**
     * @var string
     */
    public const LABEL_NOTIFY_ME = 'availability_notification.notify_me';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'availabilityNotificationSubscriptionForm';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AvailabilityNotificationSubscriptionTransfer::class,
            'attr' => [
                'id' => static::FORM_ID,
            ],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
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
    protected function addEmailField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_EMAIL, EmailType::class, [
            'label' => static::LABEL_NOTIFY_ME,
            'attr' => ['placeholder' => static::PLACEHOLDER_EMAIL_ADDRESS],
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
    protected function addSkuField(FormBuilderInterface $builder)
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
