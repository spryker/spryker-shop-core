<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductGroupWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductGroupWidget\Dependency\Client\ProductGroupWidgetToProductGroupStorageClientBridge;
use SprykerShop\Yves\ProductGroupWidget\Dependency\Client\ProductGroupWidgetToProductStorageClientBridge;

class ProductGroupWidgetDependencyProvider extends AbstractBundleDependencyProvider
{

    public const CLIENT_PRODUCT_GROUP_STORAGE = 'CLIENT_PRODUCT_GROUP_STORAGE';
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addProductGroupStorageClient($container);
        $container = $this->addProductStorageClient($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addProductGroupStorageClient($container)
    {
        $container[static::CLIENT_PRODUCT_GROUP_STORAGE] = function (Container $container) {
            return new ProductGroupWidgetToProductGroupStorageClientBridge($container->getLocator()->productGroupStorage()->client());
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addProductStorageClient($container)
    {
        $container[static::CLIENT_PRODUCT_STORAGE] = function (Container $container) {
            return new ProductGroupWidgetToProductStorageClientBridge($container->getLocator()->productStorage()->client());
        };

        return $container;
    }

}
