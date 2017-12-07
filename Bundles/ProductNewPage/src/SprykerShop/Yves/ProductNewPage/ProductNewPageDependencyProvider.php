<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ProductNewPage;

use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class ProductNewPageDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_SEARCH = 'CLIENT_SEARCH';
    const CLIENT_PRODUCT_NEW = 'CLIENT_PRODUCT_NEW';
    const CLIENT_COLLECTOR = 'CLIENT_COLLECTOR';
    const STORE = 'STORE';
    const PLUGIN_PRODUCT_NEW_PAGE_WIDGETS = 'PLUGIN_PRODUCT_NEW_PAGE_WIDGETS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addSearchClient($container);
        $container = $this->addProductNewClient($container);
        $container = $this->addCollectorClient($container);
        $container = $this->addStore($container);
        $container = $this->addProductNewPageWidgetPlugins($container);

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
            return $container->getLocator()->search()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductNewClient(Container $container)
    {
        $container[self::CLIENT_PRODUCT_NEW] = function (Container $container) {
            return $container->getLocator()->productNew()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCollectorClient(Container $container)
    {
        $container[self::CLIENT_COLLECTOR] = function (Container $container) {
            return $container->getLocator()->collector()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStore($container)
    {
        $container[self::STORE] = function (Container $container) {
            return Store::getInstance();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductNewPageWidgetPlugins($container)
    {
        $container[self::PLUGIN_PRODUCT_NEW_PAGE_WIDGETS] = function (Container $container) {
            return $this->getProductNewPageWidgetPlugins();
        };

        return $container;
    }

    /**
     * @return string[]
     */
    protected function getProductNewPageWidgetPlugins(): array
    {
        return [];
    }
}
