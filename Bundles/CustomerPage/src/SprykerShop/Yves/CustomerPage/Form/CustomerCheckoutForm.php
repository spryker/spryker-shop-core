<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Form;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerCheckoutForm extends AbstractType
{
    public const SUB_FORM_CUSTOMER = 'customer';
    public const OPTIONS_SUB_FORM_CUSTOMER = [
        'data_class' => CustomerTransfer::class,
        'property_path' => 'customer',
    ];

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::SUB_FORM_CUSTOMER);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $subform = $options[static::SUB_FORM_CUSTOMER];

        if ($subform instanceof FormBuilderInterface) {
            $builder->add($subform);

            return;
        }

        $builder->add(static::SUB_FORM_CUSTOMER, $subform, static::OPTIONS_SUB_FORM_CUSTOMER);
    }
}
