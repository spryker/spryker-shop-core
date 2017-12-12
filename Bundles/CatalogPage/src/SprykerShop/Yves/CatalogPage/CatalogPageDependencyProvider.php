<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CatalogPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToCatalogClientBridge;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToCategoryClientBridge;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToLocaleClientBridge;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToSearchClientBridge;

class CatalogPageDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_LOCALE = 'CLIENT_LOCALE';
    const CLIENT_SEARCH = 'CLIENT_SEARCH';
    const CLIENT_CATEGORY = 'CLIENT_CATEGORY';
    const CLIENT_CATALOG = 'CLIENT_CATALOG';
    const PLUGIN_CATALOG_PAGE_WIDGETS = 'PLUGIN_CATALOG_PAGE_WIDGETS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addSearchClient($container);
        $container = $this->addCategoryClient($container);
        $container = $this->addLocaleClient($container);
        $container = $this->addCatalogClient($container);
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
    protected function addCategoryClient(Container $container)
    {
        $container[static::CLIENT_CATEGORY] = function (Container $container) {
            return new CatalogPageToCategoryClientBridge($container->getLocator()->category()->client());
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
