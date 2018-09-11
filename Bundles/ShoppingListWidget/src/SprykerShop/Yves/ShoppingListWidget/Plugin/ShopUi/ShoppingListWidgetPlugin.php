<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Plugin\ShopUi;

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShopUi\Dependency\Plugin\ShoppingListWidget\ShoppingListWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetFactory getFactory()
 */
class ShoppingListWidgetPlugin extends AbstractWidgetPlugin implements ShoppingListWidgetPluginInterface
{
    /**
     * @return void
     */
    public function initialize(): void
    {
        $this->addShoppingListCollectionParameter();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public static function getTemplate()
    {
        return '@ShoppingListWidget/views/shopping-list-shop-list/shopping-list-shop-list.twig';
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public static function getName()
    {
        return static::NAME;
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    protected function getCustomerShoppingListCollection(): ShoppingListCollectionTransfer
    {
        return $this->getFactory()
            ->getShoppingListSessionClient()
            ->getCustomerShoppingListCollection();
    }

    /**
     * @return void
     */
    protected function addShoppingListCollectionParameter(): void
    {
        $this->addParameter('shoppingListCollection', $this->getCustomerShoppingListCollection());
    }
}
