<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartCodeWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use SprykerShop\Yves\CartCodeWidget\Plugin\Provider\CartCodeWidgetControllerProvider;
use SprykerShop\Yves\DiscountWidget\Plugin\Provider\DiscountWidgetControllerProvider;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CartCodeForm extends AbstractType
{
    public const FORM_NAME = 'cartCodeForm';
    public const FIELD_CODE = 'code';

    /**
     * @return string
     */
    public function getBlockPrefix()
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
        $this->addCodeField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCodeField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_CODE, TextType::class, [
            'label' => 'cart.code.enter-code',
            'required' => false,
        ]);

        return $this;
    }
}
