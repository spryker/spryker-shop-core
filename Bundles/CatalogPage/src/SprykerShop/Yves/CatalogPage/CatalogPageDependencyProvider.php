<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage;

use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToCatalogClientBridge;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToCategoryStorageClientBridge;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToLocaleClientBridge;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToProductCategoryFilterClientBridge;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToProductCategoryFilterStorageClientBridge;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToSearchClientBridge;

class CatalogPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';
    public const CLIENT_SEARCH = 'CLIENT_SEARCH';
    public const CLIENT_CATEGORY_STORAGE = 'CLIENT_CATEGORY_STORAGE';
    public const CLIENT_CATALOG = 'CLIENT_CATALOG';
    public const CLIENT_PRODUCT_CATEGORY_FILTER = 'CLIENT_PRODUCT_CATEGORY_FILTER';
    public const CLIENT_PRODUCT_CATEGORY_FILTER_STORAGE = 'CLIENT_PRODUCT_CATEGORY_FILTER_STORAGE';

    public const PLUGIN_CATALOG_PAGE_WIDGETS = 'PLUGIN_CATALOG_PAGE_WIDGETS';

    public const STORE = 'STORE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addSearchClient($container);
        $container = $this->addCategoryStorageClient($container);
        $container = $this->addLocaleClient($container);
        $container = $this->addCatalogClient($container);
        $container = $this->addProductCategoryFilterClient($container);
        $container = $this->addProductCategoryFilterStorageClient($container);
        $container = $this->addStore($container);
        $container = $this->addCatalogPageWidgetPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSearchClient(Container $container)
    {
        $container[self::CLIENT_SEARCH] = function (Container $container) {
            return new CatalogPageToSearchClientBridge($container->getLocator()->search()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCategoryStorageClient(Container $container)
    {
        $container[static::CLIENT_CATEGORY_STORAGE] = function (Container $container) {
            return new CatalogPageToCategoryStorageClientBridge($container->getLocator()->categoryStorage()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addLocaleClient(Container $container)
    {
        $container[static::CLIENT_LOCALE] = function (Container $container) {
            return new CatalogPageToLocaleClientBridge($container->getLocator()->locale()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCatalogClient(Container $container)
    {
        $container[static::CLIENT_CATALOG] = function (Container $container) {
            return new CatalogPageToCatalogClientBridge($container->getLocator()->catalog()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductCategoryFilterClient(Container $container)
    {
        $container[static::CLIENT_PRODUCT_CATEGORY_FILTER] = function (Container $container) {
            return new CatalogPageToProductCategoryFilterClientBridge($container->getLocator()->productCategoryFilter()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductCategoryFilterStorageClient(Container $container)
    {
        $container[static::CLIENT_PRODUCT_CATEGORY_FILTER_STORAGE] = function (Container $container) {
            return new CatalogPageToProductCategoryFilterStorageClientBridge($container->getLocator()->productCategoryFilterStorage()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStore($container): Container
    {
        $container->set(static::STORE, function () {
            return Store::getInstance();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCatalogPageWidgetPlugins(Container $container)
    {
        $container[static::PLUGIN_CATALOG_PAGE_WIDGETS] = function () {
            return $this->getCatalogPageWidgetPlugins();
        };

        return $container;
    }

    /**
     * @return string[]
     */
    protected function getCatalogPageWidgetPlugins(): array
    {
        return [];
    }
}
