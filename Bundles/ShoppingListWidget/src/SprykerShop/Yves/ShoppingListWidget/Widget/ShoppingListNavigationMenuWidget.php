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
class ShoppingListNavigationMenuWidget extends AbstractWidget
{
    use PermissionAwareTrait;

    protected const PARAMETER_SHOPPING_LIST_COLLECTION = 'shoppingListCollection';

    public function __construct()
    {
        $this->addShoppingListCollectionParameter();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ShoppingListNavigationMenuWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ShoppingListWidget/views/shopping-list-shop-list/shopping-list-shop-list.twig';
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
        $this->addParameter(static::PARAMETER_SHOPPING_LIST_COLLECTION, $this->getCustomerShoppingListCollection());
    }
}
