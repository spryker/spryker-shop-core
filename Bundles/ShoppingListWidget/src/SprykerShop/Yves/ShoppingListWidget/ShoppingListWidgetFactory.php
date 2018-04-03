<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToCustomerClientInterface;
use SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToShoppingListClientInterface;

/**
 * @method \SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetConfig getConfig()
 */
class ShoppingListWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToShoppingListClientInterface
     */
    public function getShoppingListClient(): ShoppingListWidgetToShoppingListClientInterface
    {
        return $this->getProvidedDependency(ShoppingListWidgetDependencyProvider::CLIENT_SHOPPING_LIST);
    }

    /**
     * @return \SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToCustomerClientInterface
     */
    public function getCustomerClient(): ShoppingListWidgetToCustomerClientInterface
    {
        return $this->getProvidedDependency(ShoppingListWidgetDependencyProvider::CLIENT_CUSTOMER);
    }
}
