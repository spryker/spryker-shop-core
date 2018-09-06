<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCompanyBusinessUnitClientBridge;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCompanyUserClientBridge;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCustomerClientBridge;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToProductStorageClientBridge;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToShoppingListClientBridge;

class ShoppingListPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    public const CLIENT_SHOPPING_LIST = 'CLIENT_SHOPPING_LIST';
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';
    public const CLIENT_COMPANY_BUSINESS_UNIT = 'CLIENT_COMPANY_BUSINESS_UNIT';
    public const CLIENT_COMPANY_USER = 'CLIENT_COMPANY_USER';
    public const PLUGIN_SHOPPING_LIST_ITEM_EXPANDERS = 'PLUGIN_SHOPPING_LIST_ITEM_EXPANDERS';
    public const PLUGIN_SHOPPING_LIST_ITEM_FORM_EXPANDERS = 'PLUGIN_SHOPPING_LIST_ITEM_FORM_EXPANDERS';
    public const PLUGIN_SHOPPING_LIST_DATA_PROVIDER_EXPANDERS = 'PLUGIN_SHOPPING_LIST_DATA_PROVIDER_EXPANDERS';
    public const PLUGIN_SHOPPING_LIST_OVERVIEW_UPDATE_PAGE_WIDGETS = 'PLUGIN_SHOPPING_LIST_OVERVIEW_UPDATE_PAGE_WIDGETS';
    public const PLUGIN_SHOPPING_LIST_WIDGETS = 'PLUGIN_SHOPPING_LIST_WIDGETS';
    public const PLUGIN_SHOPPING_LIST_VIEW_WIDGETS = 'PLUGIN_SHOPPING_LIST_VIEW_WIDGETS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addCustomerClient($container);
        $container = $this->addShoppingListClient($container);
        $container = $this->addProductStorageClient($container);
        $container = $this->addCompanyBusinessUnitClient($container);
        $container = $this->addCompanyUserClient($container);
        $container = $this->addShoppingListItemExpanderPlugins($container);
        $container = $this->addShoppingListWidgetPlugins($container);
        $container = $this->addShoppingListViewWidgetPlugins($container);
        $container = $this->addShoppingListItemFormExpanderPlugins($container);
        $container = $this->addShoppingListDataProviderExpanderPlugins($container);
        $container = $this->addShoppingListOverviewUpdatePageWidgets($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container[self::CLIENT_CUSTOMER] = function (Container $container) {
            return new ShoppingListPageToCustomerClientBridge($container->getLocator()->customer()->client());
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
        $container[self::CLIENT_SHOPPING_LIST] = function (Container $container) {
            return new ShoppingListPageToShoppingListClientBridge($container->getLocator()->shoppingList()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductStorageClient(Container $container): Container
    {
        $container[self::CLIENT_PRODUCT_STORAGE] = function (Container $container) {
            return new ShoppingListPageToProductStorageClientBridge($container->getLocator()->productStorage()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCompanyBusinessUnitClient(Container $container): Container
    {
        $container[self::CLIENT_COMPANY_BUSINESS_UNIT] = function (Container $container) {
            return new ShoppingListPageToCompanyBusinessUnitClientBridge($container->getLocator()->companyBusinessUnit()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCompanyUserClient(Container $container): Container
    {
        $container[self::CLIENT_COMPANY_USER] = function (Container $container) {
            return new ShoppingListPageToCompanyUserClientBridge($container->getLocator()->companyUser()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addShoppingListItemExpanderPlugins(Container $container): Container
    {
        $container[self::PLUGIN_SHOPPING_LIST_ITEM_EXPANDERS] = function () {
            return $this->getShoppingListItemExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface[]
     */
    protected function getShoppingListItemExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addShoppingListWidgetPlugins(Container $container): Container
    {
        $container[self::PLUGIN_SHOPPING_LIST_WIDGETS] = function () {
            return $this->getShoppingListWidgetPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addShoppingListViewWidgetPlugins(Container $container): Container
    {
        $container[static::PLUGIN_SHOPPING_LIST_VIEW_WIDGETS] = function () {
            return $this->getShoppingListViewWidgetPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addShoppingListItemFormExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGIN_SHOPPING_LIST_ITEM_FORM_EXPANDERS] = function () {
            return $this->getShoppingListItemFormWidgetPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addShoppingListDataProviderExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGIN_SHOPPING_LIST_DATA_PROVIDER_EXPANDERS] = function () {
            return $this->getShoppingListDataProviderExpanderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addShoppingListOverviewUpdatePageWidgets(Container $container): Container
    {
        $container[static::PLUGIN_SHOPPING_LIST_OVERVIEW_UPDATE_PAGE_WIDGETS] = function () {
            return $this->getShoppingListOverviewUpdatePageWidgets();
        };

        return $container;
    }

    /**
     * Returns a list of widget plugin class names that implement
     * \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface.
     *
     * @return string[]
     */
    protected function getShoppingListWidgetPlugins(): array
    {
        return [];
    }

    /**
     * Returns a list of widget plugin class names that implement
     * \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface.
     *
     * @return string[]
     */
    protected function getShoppingListViewWidgetPlugins(): array
    {
        return [];
    }

    /**
     * @return \SprykerShop\Yves\ShoppingListPageExtension\Dependency\Plugin\ShoppingListItemFormExpanderPluginInterface[]
     */
    protected function getShoppingListItemFormWidgetPlugins(): array
    {
        return [];
    }

    /**
     * @return \SprykerShop\Yves\ShoppingListPageExtension\Dependency\Plugin\ShoppingListDataProviderExpanderPluginInterface[]
     */
    protected function getShoppingListDataProviderExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return string[]
     */
    protected function getShoppingListOverviewUpdatePageWidgets(): array
    {
        return [];
    }
}
