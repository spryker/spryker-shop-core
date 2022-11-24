<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToCatalogClientBridge;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToCategoryStorageClientBridge;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToLocaleClientBridge;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToProductCategoryFilterClientBridge;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToProductCategoryFilterStorageClientBridge;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToSearchClientBridge;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToStoreClientBridge;
use SprykerShop\Yves\CatalogPage\Dependency\Service\CatalogPageToUtilNumberServiceBridge;

/**
 * @method \SprykerShop\Yves\CatalogPage\CatalogPageConfig getConfig()
 */
class CatalogPageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';

    /**
     * @var string
     */
    public const CLIENT_SEARCH = 'CLIENT_SEARCH';

    /**
     * @var string
     */
    public const CLIENT_CATEGORY_STORAGE = 'CLIENT_CATEGORY_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_CATALOG = 'CLIENT_CATALOG';

    /**
     * @var string
     */
    public const CLIENT_PRODUCT_CATEGORY_FILTER = 'CLIENT_PRODUCT_CATEGORY_FILTER';

    /**
     * @var string
     */
    public const CLIENT_PRODUCT_CATEGORY_FILTER_STORAGE = 'CLIENT_PRODUCT_CATEGORY_FILTER_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @var string
     */
    public const PLUGIN_CATALOG_PAGE_WIDGETS = 'PLUGIN_CATALOG_PAGE_WIDGETS';

    /**
     * @var string
     */
    public const PLUGIN_APPLICATION = 'PLUGIN_APPLICATION';

    /**
     * @var string
     */
    public const SERVICE_UTIL_NUMBER = 'SERVICE_UTIL_NUMBER';

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
        $container = $this->addStoreClient($container);
        $container = $this->addCatalogPageWidgetPlugins($container);
        $container = $this->addApplication($container);
        $container = $this->addUtilNumberService($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSearchClient(Container $container)
    {
        $container->set(static::CLIENT_SEARCH, function (Container $container) {
            return new CatalogPageToSearchClientBridge($container->getLocator()->search()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCategoryStorageClient(Container $container)
    {
        $container->set(static::CLIENT_CATEGORY_STORAGE, function (Container $container) {
            return new CatalogPageToCategoryStorageClientBridge($container->getLocator()->categoryStorage()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addLocaleClient(Container $container)
    {
        $container->set(static::CLIENT_LOCALE, function (Container $container) {
            return new CatalogPageToLocaleClientBridge($container->getLocator()->locale()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCatalogClient(Container $container)
    {
        $container->set(static::CLIENT_CATALOG, function (Container $container) {
            return new CatalogPageToCatalogClientBridge($container->getLocator()->catalog()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductCategoryFilterClient(Container $container)
    {
        $container->set(static::CLIENT_PRODUCT_CATEGORY_FILTER, function (Container $container) {
            return new CatalogPageToProductCategoryFilterClientBridge($container->getLocator()->productCategoryFilter()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductCategoryFilterStorageClient(Container $container)
    {
        $container->set(static::CLIENT_PRODUCT_CATEGORY_FILTER_STORAGE, function (Container $container) {
            return new CatalogPageToProductCategoryFilterStorageClientBridge($container->getLocator()->productCategoryFilterStorage()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return new CatalogPageToStoreClientBridge($container->getLocator()->store()->client());
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
        $container->set(static::PLUGIN_CATALOG_PAGE_WIDGETS, function () {
            return $this->getCatalogPageWidgetPlugins();
        });

        return $container;
    }

    /**
     * @return array<string>
     */
    protected function getCatalogPageWidgetPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addApplication(Container $container): Container
    {
        $container->set(static::PLUGIN_APPLICATION, function () {
            $pimplePlugin = new Pimple();

            return $pimplePlugin->getApplication();
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
            return new CatalogPageToUtilNumberServiceBridge(
                $container->getLocator()->utilNumber()->service(),
            );
        });

        return $container;
    }
}
