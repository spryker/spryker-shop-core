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
        $this->addParameter('sku', $sku)
            ->addParameter('isDisabled', $isDisabled)
            ->addParameter('shoppingListCollection', $this->getShoppingListCollection());
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

    /**
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    protected function getShoppingListCollection(): ShoppingListCollectionTransfer
    {
        $shoppingListCollection = new ShoppingListCollectionTransfer();

        if (!$this->getFactory()->getCustomerClient()->isLoggedIn()) {
            return $shoppingListCollection;
        }

        $customerShoppingListCollection = $this->getFactory()->getShoppingListClient()->getCustomerShoppingListCollection();

        foreach ($customerShoppingListCollection->getShoppingLists() as $shoppingList) {
            if ($this->can('WriteShoppingListPermissionPlugin', $shoppingList->getIdShoppingList())) {
                $shoppingListCollection->addShoppingList($shoppingList);
            }
        }

        return $shoppingListCollection;
    }
}
