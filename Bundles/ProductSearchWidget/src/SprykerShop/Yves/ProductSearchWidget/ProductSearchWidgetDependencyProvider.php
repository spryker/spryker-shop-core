<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToLocaleClientBridge;
use SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToProductPageSearchClientBridge;

class ProductSearchWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_PAGE_SEARCH = 'CLIENT_PRODUCT_PAGE_SEARCH';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addProductPageSearchClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductPageSearchClient(Container $container): Container
    {
        $container[static::CLIENT_PRODUCT_PAGE_SEARCH] = function (Container $container) {
            return new ProductSearchWidgetToProductPageSearchClientBridge(
                $container->getLocator()->productPageSearch()->client()
            );
        };

        return $container;
    }
}
