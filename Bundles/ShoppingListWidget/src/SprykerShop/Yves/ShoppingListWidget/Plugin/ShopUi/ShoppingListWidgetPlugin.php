<?php

namespace SprykerShop\Yves\ShoppingListWidget\Plugin\ShopUi;

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShopUi\Dependency\Plugin\ShoppingListWidget\ShoppingListWidgetPluginInterface;


/**
 * @method \SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetFactory getFactory()
 */
class ShoppingListWidgetPlugin extends AbstractWidgetPlugin implements ShoppingListWidgetPluginInterface
{
    /**
     *
     */
    public function initialize(): void
    {
        $this->addParameter('shoppingListCollection', $this->getCustomerShoppingListCollection());
    }

    /**
     * @inheritDoc
     */
    public static function getTemplate()
    {
        return '@ShoppingListWidget/views/shopping-list-shop-list/shopping-list-shop-list.twig';
    }

    /**
     * @inheritDoc
     */
    public static function getName()
    {
        return static::NAME;
    }

    protected function getCustomerShoppingListCollection(): ShoppingListCollectionTransfer
    {
        $shoppingListCollection = new ShoppingListCollectionTransfer();

        if (!$this->getFactory()->getCustomerClient()->isLoggedIn()) {
            return $shoppingListCollection;
        }

        return $this->getFactory()
            ->getShoppingListSessionClient()
            ->getCustomerShoppingListCollection();
    }
}
