<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget\Plugin\ShoppingListPage;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ProductOptionWidget\Form\ShoppingListItemProductOptionForm;
use SprykerShop\Yves\ShoppingListPageExtension\Dependency\Plugin\ShoppingListItemFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @method \SprykerShop\Yves\ProductOptionWidget\ProductOptionWidgetFactory getFactory()
 */
class ShoppingListItemProductOptionFormExpanderPlugin extends AbstractPlugin implements ShoppingListItemFormExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Extends shopping list item form with product options form.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $productOptionGroupStorageTransfers = $this->getFactory()
                ->createShoppingListItemProductOptionFormDataProvider()
                ->findProductOptionGroupsByShoppingListItem($event->getData());

            if ($productOptionGroupStorageTransfers->count()) {
                $fieldOptions = [
                    ShoppingListItemProductOptionForm::PRODUCT_OPTION_GROUP_KEY => $productOptionGroupStorageTransfers,
                ];

                $event->getForm()->add(
                    ShoppingListItemTransfer::PRODUCT_OPTIONS,
                    ShoppingListItemProductOptionForm::class,
                    $fieldOptions
                );
            }
        });
    }
}
