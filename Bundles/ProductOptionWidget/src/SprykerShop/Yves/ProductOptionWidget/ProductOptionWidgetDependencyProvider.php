<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductOptionWidget\Dependency\Client\ProductOptionWidgetToProductOptionStorageClientBridge;

class ProductOptionWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_PRODUCT_OPTION_STORAGE = 'CLIENT_PRODUCT_OPTION_STORAGE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addProductOptionClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductOptionClient(Container $container)
    {
        $container[self::CLIENT_PRODUCT_OPTION_STORAGE] = function (Container $container) {
            return new ProductOptionWidgetToProductOptionStorageClientBridge($container->getLocator()->productOptionStorage()->client());
        };

        return $container;
    }
}
