<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Client\ProductBundleWidget;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class ProductBundleWidgetDependencyProvider extends AbstractDependencyProvider
{
    const CLIENT_PRODUCT_BUNDLE = 'CLIENT_PRODUCT_BUNDLE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addProductBundleClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductBundleClient(Container $container): Container
    {
        $container[self::CLIENT_PRODUCT_BUNDLE] = function (Container $container) {
            return $container->getLocator()->productBundle()->client(); // TODO: bridge
        };
        
        return $container;
    }
}
