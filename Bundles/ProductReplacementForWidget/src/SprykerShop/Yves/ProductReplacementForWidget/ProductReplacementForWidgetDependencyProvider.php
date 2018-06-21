<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReplacementForWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductReplacementForWidget\Dependency\Client\ProductReplacementForWidgetToProductAlternativeStorageClientBridge;
use SprykerShop\Yves\ProductReplacementForWidget\Dependency\Client\ProductReplacementForWidgetToProductStorageClientBridge;

class ProductReplacementForWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_ALTERNATIVE_STORAGE = 'CLIENT_PRODUCT_ALTERNATIVE_STORAGE';
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addProductAlternativeStorageClient($container);
        $container = $this->addProductStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductAlternativeStorageClient(Container $container): Container
    {
        $container[static::CLIENT_PRODUCT_ALTERNATIVE_STORAGE] = function (Container $container) {
            return new ProductReplacementForWidgetToProductAlternativeStorageClientBridge(
                $container->getLocator()->productAlternativeStorage()->client()
            );
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
        $container[static::CLIENT_PRODUCT_STORAGE] = function (Container $container) {
            return new ProductReplacementForWidgetToProductStorageClientBridge(
                $container->getLocator()->productStorage()->client()
            );
        };

        return $container;
    }
}
