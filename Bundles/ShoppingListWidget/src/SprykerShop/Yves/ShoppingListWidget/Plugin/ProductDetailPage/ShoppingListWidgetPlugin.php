<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Plugin\ProductDetailPage;

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ShoppingListWidget\ShoppingListWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetFactory getFactory()
 */
class ShoppingListWidgetPlugin extends AbstractWidgetPlugin implements ShoppingListWidgetPluginInterface
{
    use PermissionAwareTrait;

    /**
     * @param string $sku
     * @param bool $isDisabled
     *
     * @return void
     */
    public function initialize(string $sku, bool $isDisabled): void
    {
        $shoppingListCollection = new ShoppingListCollectionTransfer();

        if ($this->getFactory()->getCustomerClient()->isLoggedIn()) {
            $shoppingListCollection = $this->getFactory()->getShoppingListClient()->getCustomerShoppingListCollection();
            $shoppingLists = $shoppingListCollection->getShoppingLists();

            foreach ($shoppingLists as $offset => $shoppingList) {
                if (!$this->can('WriteShoppingListPermissionPlugin', $shoppingList->getIdShoppingList())) {
                    $shoppingLists->offsetUnset($offset);
                }
            }

            $shoppingListCollection->setShoppingLists($shoppingLists);
        }

        $this
            ->addParameter('sku', $sku)
            ->addParameter('isDisabled', $isDisabled)
            ->addParameter('shoppingListCollection', $shoppingListCollection);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ShoppingListWidget/views/shopping-list/shopping-list.twig';
    }
}
