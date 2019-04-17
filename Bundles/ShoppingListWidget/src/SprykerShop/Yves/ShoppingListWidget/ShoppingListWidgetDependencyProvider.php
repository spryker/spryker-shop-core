<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToAvailabilityClientBridge;
use SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToCustomerClientBridge;
use SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToProductQuantityStorageClientBridge;
use SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToShoppingListClientBridge;
use SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToShoppingListSessionClientBridge;

class ShoppingListWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_AVAILABILITY = 'CLIENT_AVAILABILITY';
    public const CLIENT_PRODUCT_QUANTITY_STORAGE = 'CLIENT_PRODUCT_QUANTITY_STORAGE';
    public const CLIENT_SHOPPING_LIST = 'CLIENT_SHOPPING_LIST';
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    public const CLIENT_SHOPPING_LIST_SESSION = 'CLIENT_SHOPPING_LIST_SESSION';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addShoppingListClient($container);
        $container = $this->addCustomerClient($container);
        $container = $this->addShoppingListSessionClient($container);
        $container = $this->addAvailabilityClient($container);
        $container = $this->addProductQuantityStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductQuantityStorageClient(Container $container): Container
    {
        $container[static::CLIENT_PRODUCT_QUANTITY_STORAGE] = function (Container $container) {
            return new ShoppingListWidgetToProductQuantityStorageClientBridge(
                $container->getLocator()->productQuantityStorage()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addAvailabilityClient(Container $container): Container
    {
        $container[static::CLIENT_AVAILABILITY] = function (Container $container) {
            return new ShoppingListWidgetToAvailabilityClientBridge(
                $container->getLocator()->availability()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addShoppingListClient(Container $container): Container
    {
        $container[static::CLIENT_SHOPPING_LIST] = function (Container $container) {
            return new ShoppingListWidgetToShoppingListClientBridge($container->getLocator()->shoppingList()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container[static::CLIENT_CUSTOMER] = function (Container $container) {
            return new ShoppingListWidgetToCustomerClientBridge($container->getLocator()->customer()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addShoppingListSessionClient(Container $container): Container
    {
        $container[static::CLIENT_SHOPPING_LIST_SESSION] = function (Container $container) {
            return new ShoppingListWidgetToShoppingListSessionClientBridge($container->getLocator()->shoppingListSession()->client());
        };

        return $container;
    }
}
