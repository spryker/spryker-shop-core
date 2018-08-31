<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderToShoppingListWidget\Form;

use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use SprykerShop\Yves\QuickOrderPage\Form\Constraint\QtyFieldConstraint;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\QuickOrderPage\QuickOrderPageFactory getFactory()
 */
class OrderItemEmbeddedForm extends AbstractType
{
    public const FIELD_SKU = 'sku';
    public const FIELD_SKU_LABEL = 'quick-order.input-label.sku';
    public const FIELD_QTY = 'qty';
    public const FIELD_QTY_LABEL = 'quick-order.input-label.qty';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addSku($builder)
            ->addQty($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuickOrderItemTransfer::class,
            'constraints' => [
                new QtyFieldConstraint(),
            ],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSku(FormBuilderInterface $builder): FormTypeInterface
    {
        $builder
            ->add(static::FIELD_SKU, TextType::class, [
                'required' => false,
                'label' => static::FIELD_SKU_LABEL,
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addQty(FormBuilderInterface $builder): FormTypeInterface
    {
        $builder->add(static::FIELD_QTY, IntegerType::class, [
            'required' => false,
            'label' => static::FIELD_QTY_LABEL,
            'attr' => ['min' => 1],
        ]);

        return $this;
    }
}
