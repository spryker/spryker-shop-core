<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountWidget\Form;

use SprykerShop\Yves\DiscountWidget\Plugin\Provider\DiscountWidgetControllerProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CartVoucherForm extends AbstractType
{
    const FORM_NAME = 'voucherForm';
    const FIELD_VOUCHER_CODE = 'voucher_code';

    /**
     * @return string
     */
    public function getName()
    {
        return self::FORM_NAME;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAction(DiscountWidgetControllerProvider::ROUTE_DISCOUNT_VOUCHER_ADD);

        $this->addVoucherCodeField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addVoucherCodeField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_VOUCHER_CODE, TextType::class, [
            'label' => 'page.checkout.finalize.enter-voucher',
            'required' => true,
        ]);

        return $this;
    }
}
