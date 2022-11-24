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
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToLocaleClientBridge;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToMultiCartClientBridge;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToPriceClientBridge;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToProductStorageClientBridge;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToShoppingListClientBridge;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToZedRequestClientBridge;
use SprykerShop\Yves\ShoppingListPage\Dependency\Service\ShoppingListPageToUtilEncodingServiceBridge;
use SprykerShop\Yves\ShoppingListPage\Dependency\Service\ShoppingListPageToUtilNumberServiceBridge;

/**
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageConfig getConfig()
 */
class ShoppingListPageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    /**
     * @var string
     */
    public const CLIENT_SHOPPING_LIST = 'CLIENT_SHOPPING_LIST';

    /**
     * @var string
     */
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_COMPANY_BUSINESS_UNIT = 'CLIENT_COMPANY_BUSINESS_UNIT';

    /**
     * @var string
     */
    public const CLIENT_COMPANY_USER = 'CLIENT_COMPANY_USER';

    /**
     * @var string
     */
    public const CLIENT_PRICE = 'CLIENT_PRICE';

    /**
     * @var string
     */
    public const PLUGIN_SHOPPING_LIST_ITEM_EXPANDERS = 'PLUGIN_SHOPPING_LIST_ITEM_EXPANDERS';

    /**
     * @var string
     */
    public const PLUGIN_SHOPPING_LIST_ITEM_FORM_EXPANDERS = 'PLUGIN_SHOPPING_LIST_ITEM_FORM_EXPANDERS';

    /**
     * @var string
     */
    public const PLUGIN_SHOPPING_LIST_FORM_DATA_PROVIDER_MAPPERS = 'PLUGIN_SHOPPING_LIST_FORM_DATA_PROVIDER_MAPPERS';

    /**
     * @var string
     */
    public const CLIENT_MULTI_CART = 'CLIENT_MULTI_CART';

    /**
     * @var string
     */
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';

    /**
     * @var string
     */
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const SERVICE_UTIL_NUMBER = 'SERVICE_UTIL_NUMBER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addCustomerClient($container);
        $container = $this->addShoppingListClient($container);
        $container = $this->addProductStorageClient($container);
        $container = $this->addCompanyBusinessUnitClient($container);
        $container = $this->addCompanyUserClient($container);
        $container = $this->addMultiCartClient($container);
        $container = $this->addZedRequestClient($container);
        $container = $this->addPriceClient($container);
        $container = $this->addLocaleClient($container);
        $container = $this->addShoppingListItemExpanderPlugins($container);
        $container = $this->addShoppingListItemFormExpanderPlugins($container);
        $container = $this->addShoppingListFormDataProviderMapperPlugins($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addUtilNumberService($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container->set(static::CLIENT_CUSTOMER, function (Container $container) {
            return new ShoppingListPageToCustomerClientBridge($container->getLocator()->customer()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addZedRequestClient(Container $container): Container
    {
        $container->set(static::CLIENT_ZED_REQUEST, function (Container $container) {
            return new ShoppingListPageToZedRequestClientBridge($container->getLocator()->zedRequest()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addShoppingListClient(Container $container): Container
    {
        $container->set(static::CLIENT_SHOPPING_LIST, function (Container $container) {
            return new ShoppingListPageToShoppingListClientBridge($container->getLocator()->shoppingList()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_STORAGE, function (Container $container) {
            return new ShoppingListPageToProductStorageClientBridge($container->getLocator()->productStorage()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCompanyBusinessUnitClient(Container $container): Container
    {
        $container->set(static::CLIENT_COMPANY_BUSINESS_UNIT, function (Container $container) {
            return new ShoppingListPageToCompanyBusinessUnitClientBridge($container->getLocator()->companyBusinessUnit()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCompanyUserClient(Container $container): Container
    {
        $container->set(static::CLIENT_COMPANY_USER, function (Container $container) {
            return new ShoppingListPageToCompanyUserClientBridge($container->getLocator()->companyUser()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPriceClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRICE, function (Container $container) {
            return new ShoppingListPageToPriceClientBridge(
                $container->getLocator()->price()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addShoppingListItemExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_SHOPPING_LIST_ITEM_EXPANDERS, function () {
            return $this->getShoppingListItemExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface>
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
    protected function addShoppingListItemFormExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_SHOPPING_LIST_ITEM_FORM_EXPANDERS, function () {
            return $this->getShoppingListItemFormExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMultiCartClient(Container $container): Container
    {
        $container->set(static::CLIENT_MULTI_CART, function (Container $container) {
            return new ShoppingListPageToMultiCartClientBridge($container->getLocator()->multiCart()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new ShoppingListPageToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addShoppingListFormDataProviderMapperPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_SHOPPING_LIST_FORM_DATA_PROVIDER_MAPPERS, function () {
            return $this->getShoppingListFormDataProviderMapperPlugins();
        });

        return $container;
    }

    /**
     * @return array<\SprykerShop\Yves\ShoppingListPageExtension\Dependency\Plugin\ShoppingListItemFormExpanderPluginInterface>
     */
    protected function getShoppingListItemFormExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\SprykerShop\Yves\ShoppingListPageExtension\Dependency\Plugin\ShoppingListFormDataProviderMapperPluginInterface>
     */
    protected function getShoppingListFormDataProviderMapperPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container->set(static::CLIENT_LOCALE, function (Container $container) {
            return new ShoppingListPageToLocaleClientBridge(
                $container->getLocator()->locale()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addUtilNumberService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_NUMBER, function (Container $container) {
            return new ShoppingListPageToUtilNumberServiceBridge(
                $container->getLocator()->utilNumber()->service(),
            );
        });

        return $container;
    }
}
