<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NewsletterWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class NewsletterSubscriptionForm extends AbstractType
{
    public const FIELD_SUBSCRIBE = 'subscribe';
    public const FORM_ID = 'subscription';

    protected const MAX_EMAIL_LENGTH_VALUE = 254;

    protected const VALIDATION_NOT_BLANK_MESSAGE = 'validation.not_blank';
    protected const VALIDATION_EMAIL_MESSAGE = 'validation.email';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'newsletterSubscriptionForm';
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
        $builder
            ->setAction('#' . self::FORM_ID);

        $this->addSubscribeField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSubscribeField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_SUBSCRIBE, EmailType::class, [
            'label' => 'newsletter.subscribe',
            'required' => false,
            'constraints' => [
                $this->createNotBlankConstraint(),
                $this->createEmailConstraint(),
                $this->createLengthConstraint(),
            ],
        ]);

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\NotBlank
     */
    protected function createNotBlankConstraint(): NotBlank
    {
        return new NotBlank(['message' => static::VALIDATION_NOT_BLANK_MESSAGE]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\Email
     */
    protected function createEmailConstraint(): Email
    {
        return new Email(['message' => static::VALIDATION_EMAIL_MESSAGE]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\Length
     */
    protected function createLengthConstraint(): Length
    {
        return new Length(['max' => static::MAX_EMAIL_LENGTH_VALUE]);
    }
}
