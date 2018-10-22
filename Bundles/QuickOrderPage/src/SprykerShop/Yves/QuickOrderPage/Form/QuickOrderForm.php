<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form;

use Generated\Shared\Transfer\QuickOrderTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\QuickOrderPage\QuickOrderPageFactory getFactory()
 */
class QuickOrderForm extends AbstractType
{
    protected const FIELD_ITEMS = 'items';

    public const SUBMIT_BUTTON_ADD_TO_CART = 'addToCart';
    public const SUBMIT_BUTTON_CREATE_ORDER = 'createOrder';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addItemsCollection($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuickOrderTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addItemsCollection(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_ITEMS,
            CollectionType::class,
            [
                'entry_type' => QuickOrderItemEmbeddedForm::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
                'entry_options' => [
                    'label' => false,
                ],
                'constraints' => [
                        $this->getFactory()->createItemsFieldConstraint(),
                ],
            ]
        );

        return $this;
    }
}
