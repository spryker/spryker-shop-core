<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductNewPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductNewPage\Dependency\Client\ProductNewPageToCatalogClientBridge;
use SprykerShop\Yves\ProductNewPage\Dependency\Client\ProductNewPageToLocaleClientBridge;
use SprykerShop\Yves\ProductNewPage\Dependency\Client\ProductNewPageToProductNewClientBridge;
use SprykerShop\Yves\ProductNewPage\Dependency\Client\ProductNewPageToUrlStorageClientBridge;

/**
 * @method \SprykerShop\Yves\ProductNewPage\ProductNewPageConfig getConfig()
 */
class ProductNewPageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';

    /**
     * @var string
     */
    public const CLIENT_PRODUCT_NEW = 'CLIENT_PRODUCT_NEW';

    /**
     * @var string
     */
    public const CLIENT_URL_STORAGE = 'CLIENT_URL_STORAGE';

    /**
     * @var string
     */
    public const PLUGIN_PRODUCT_NEW_PAGE_WIDGETS = 'PLUGIN_PRODUCT_NEW_PAGE_WIDGETS';

    /**
     * @var string
     */
    public const CLIENT_CATALOG = 'CLIENT_CATALOG';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addProductNewClient($container);
        $container = $this->addUrlStorageClient($container);
        $container = $this->addLocaleClient($container);
        $container = $this->addCatalogClient($container);
        $container = $this->addProductNewPageWidgetPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductNewClient(Container $container)
    {
        $container->set(static::CLIENT_PRODUCT_NEW, function (Container $container) {
            return new ProductNewPageToProductNewClientBridge($container->getLocator()->productNew()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addUrlStorageClient(Container $container)
    {
        $container->set(static::CLIENT_URL_STORAGE, function (Container $container) {
            return new ProductNewPageToUrlStorageClientBridge($container->getLocator()->urlStorage()->client());
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
            return new ProductNewPageToCatalogClientBridge($container->getLocator()->catalog()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductNewPageWidgetPlugins($container)
    {
        $container->set(static::PLUGIN_PRODUCT_NEW_PAGE_WIDGETS, function () {
            return $this->getProductNewPageWidgetPlugins();
        });

        return $container;
    }

    /**
     * @return array<string>
     */
    protected function getProductNewPageWidgetPlugins(): array
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
            return new ProductNewPageToLocaleClientBridge(
                $container->getLocator()->locale()->client(),
            );
        });

        return $container;
    }
}
