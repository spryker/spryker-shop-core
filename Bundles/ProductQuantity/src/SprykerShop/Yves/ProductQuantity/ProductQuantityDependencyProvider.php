<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductQuantity;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductQuantity\Dependency\Client\ProductQuantityToProductQuantityStorageClientBridge;
use SprykerShop\Yves\ProductQuantity\Dependency\Client\ProductQuantityToProductQuantityStorageClientInterface;

class ProductQuantityDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_QUANTITY_STORAGE = 'CLIENT_PRODUCT_QUANTITY_STORAGE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addProductStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductStorageClient(Container $container)
    {
        $container[self::CLIENT_PRODUCT_QUANTITY_STORAGE] = function (Container $container): ProductQuantityToProductQuantityStorageClientInterface {
            return new ProductQuantityToProductQuantityStorageClientBridge($container->getLocator()->productQuantityStorage()->client());
        };

        return $container;
    }
}
