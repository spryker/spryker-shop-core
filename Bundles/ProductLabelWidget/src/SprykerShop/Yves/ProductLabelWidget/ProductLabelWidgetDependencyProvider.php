<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductLabelWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductLabelWidget\Dependency\Client\ProductLabelWidgetToProductLabelStorageClientBridge;
use SprykerShop\Yves\ProductLabelWidget\Dependency\Client\ProductLabelWidgetToStoreClientBridge;

class ProductLabelWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_LABEL_STORAGE = 'CLIENT_PRODUCT_LABEL_STORAGE';
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addProductLabelStorageClient($container);
        $container = $this->addStoreClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductLabelStorageClient(Container $container): Container
    {
        $container[self::CLIENT_PRODUCT_LABEL_STORAGE] = function (Container $container) {
            return new ProductLabelWidgetToProductLabelStorageClientBridge($container->getLocator()->productLabelStorage()->client());
        };

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
            return new ProductLabelWidgetToStoreClientBridge($container->getLocator()->store()->client());
        });

        return $container;
    }
}
