<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDiscontinuedWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductDiscontinuedWidget\Dependency\Client\ProductDiscontinuedWidgetToProductDiscontinuedStorageClientBridge;

class ProductDiscontinuedWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_DISCONTINUED_STORAGE = 'CLIENT_PRODUCT_DISCONTINUED_STORAGE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addProductDiscontinuedStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductDiscontinuedStorageClient($container): Container
    {
        $container[static::CLIENT_PRODUCT_DISCONTINUED_STORAGE] = function (Container $container) {
            return new ProductDiscontinuedWidgetToProductDiscontinuedStorageClientBridge(
                $container->getLocator()->productDiscontinuedStorage()->client()
            );
        };

        return $container;
    }
}
