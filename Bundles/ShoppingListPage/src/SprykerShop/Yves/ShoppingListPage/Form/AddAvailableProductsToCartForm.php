<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class AddAvailableProductsToCartForm extends AbstractType
{
    public const SHOPPING_LIST_ITEM_COLLECTION = 'shoppingListItemCollection';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addShoppingListItemCollectionField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addShoppingListItemCollectionField(FormBuilderInterface $builder): self
    {
        $builder->add(self::SHOPPING_LIST_ITEM_COLLECTION, CollectionType::class, [
            'label' => false,
            'entry_type' => ShoppingListItemForm::class,
            'entry_options' => [
                'label' => false,
            ],
            'allow_add' => true,
            'required' => false,
        ]);

        return $this;
    }
}
