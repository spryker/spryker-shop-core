<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Widget;

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetFactory getFactory()
 */
class AddToShoppingListWidget extends AbstractWidget
{
    use PermissionAwareTrait;

    protected const PARAMETER_SKU = 'sku';
    protected const PARAMETER_IS_DISABLED = 'isDisabled';
    protected const PARAMETER_SHOPPING_LIST_COLLECTION = 'shoppingListCollection';

    /**
     * @param string $sku
     * @param bool $isDisabled
     */
    public function __construct(string $sku, bool $isDisabled)
    {
        $this->addParameter(static::PARAMETER_SKU, $sku)
            ->addParameter(static::PARAMETER_IS_DISABLED, $isDisabled)
            ->addParameter(static::PARAMETER_SHOPPING_LIST_COLLECTION, $this->getShoppingListCollection());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'AddToShoppingListWidget';
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

        $customerShoppingListCollection = $this->getFactory()->getShoppingListSessionClient()->getCustomerShoppingListCollection();

        foreach ($customerShoppingListCollection->getShoppingLists() as $shoppingList) {
            if ($this->can('WriteShoppingListPermissionPlugin', $shoppingList->getIdShoppingList())) {
                $shoppingListCollection->addShoppingList($shoppingList);
            }
        }

        return $shoppingListCollection;
    }
}
