<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuickOrderForm extends AbstractType
{
    public const FIELD_ITEMS = 'items';
    public const FIELD_TEXT_ORDER = 'textOrder';

    public const SUBMIT_BUTTON_ADD_TO_CART = 'addToCart';
    public const SUBMIT_BUTTON_CREATE_ORDER = 'createOrder';
    public const SUBMIT_BUTTON_VERIFY = 'verifyTextOrder';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(static::FIELD_ITEMS, CollectionType::class, [
                'entry_type' => OrderItemEmbeddedForm::class,
                'allow_add' => true,
            ])
            ->add(static::FIELD_TEXT_ORDER, TextareaType::class, [
                'required' => false,
            ]);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuickOrderData::class,
        ]);
    }
}
