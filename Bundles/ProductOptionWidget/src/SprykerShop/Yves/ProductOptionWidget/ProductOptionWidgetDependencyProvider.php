<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductOptionWidget\Dependency\Client\ProductOptionWidgetToProductOptionClientBridge;

class ProductOptionWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_PRODUCT_OPTION = 'CLIENT_PRODUCT_OPTION';

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
        $container[self::CLIENT_PRODUCT_OPTION] = function (Container $container) {
            return new ProductOptionWidgetToProductOptionClientBridge($container->getLocator()->productOption()->client());
        };

        return $container;
    }
}
