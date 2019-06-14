<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductQuantityWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductQuantityWidget\Dependency\Client\ProductQuantityWidgetToProductQuantityStorageClientBridge;
use SprykerShop\Yves\ProductQuantityWidget\Dependency\Service\ProductQuantityWidgetToProductQuantityServiceBridge;

class ProductQuantityWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_QUANTITY_STORAGE = 'CLIENT_PRODUCT_QUANTITY_STORAGE';
    public const SERVICE_PRODUCT_QUANTITY = 'SERVICE_PRODUCT_QUANTITY';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addProductQuantityStorageClient($container);
        $container = $this->addProductQuantityService($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductQuantityStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_QUANTITY_STORAGE, function (Container $container) {
            return new ProductQuantityWidgetToProductQuantityStorageClientBridge(
                $container->getLocator()->productQuantityStorage()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductQuantityService(Container $container): Container
    {
        $container->set(static::SERVICE_PRODUCT_QUANTITY, function (Container $container) {
            return new ProductQuantityWidgetToProductQuantityServiceBridge(
                $container->getLocator()->productQuantity()->service()
            );
        });

        return $container;
    }
}
