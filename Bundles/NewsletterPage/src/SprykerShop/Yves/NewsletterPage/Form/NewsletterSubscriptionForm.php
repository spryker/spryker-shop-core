<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NewsletterPage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class NewsletterSubscriptionForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_SUBSCRIBE = 'subscribe';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'newsletterSubscriptionForm';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addSubscribeField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSubscribeField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_SUBSCRIBE, CheckboxType::class, [
            'label' => 'customer.newsletter.subscription_agreement',
            'required' => false,
        ]);

        return $this;
    }
}
