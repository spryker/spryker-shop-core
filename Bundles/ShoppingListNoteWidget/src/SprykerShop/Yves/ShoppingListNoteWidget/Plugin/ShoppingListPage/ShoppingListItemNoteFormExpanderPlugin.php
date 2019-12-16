<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListNoteWidget\Plugin\ShoppingListPage;

use Generated\Shared\Transfer\ShoppingListItemNoteTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ShoppingListNoteWidget\Form\ShoppingListItemNoteForm;
use SprykerShop\Yves\ShoppingListPageExtension\Dependency\Plugin\ShoppingListItemFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerShop\Yves\ShoppingListNoteWidget\ShoppingListNoteWidgetFactory getFactory()
 */
class ShoppingListItemNoteFormExpanderPlugin extends AbstractPlugin implements ShoppingListItemFormExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds ShoppingListItemNote form fields to builder using ShoppingListItemNoteForm
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            ShoppingListItemTransfer::SHOPPING_LIST_ITEM_NOTE,
            ShoppingListItemNoteForm::class,
            [
                'data_class' => ShoppingListItemNoteTransfer::class,
                'label' => false,
            ]
        );
    }
}
