<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CheckoutVoucherForm extends AbstractType
{
    public const FORM_NAME = 'voucherForm';
    public const FIELD_VOUCHER_DISCOUNTS = 'voucherDiscounts';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return static::FORM_NAME;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAction('/checkout/add-voucher');

        $this->addVoucherCodeField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addVoucherCodeField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_VOUCHER_DISCOUNTS, TextType::class, [
            'label' => 'page.checkout.finalize.enter-voucher',
            'required' => true,
        ]);

        return $this;
    }
}
