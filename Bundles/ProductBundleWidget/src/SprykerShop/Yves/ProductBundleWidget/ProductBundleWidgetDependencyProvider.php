<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class ProductBundleWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_PRODUCT_BUNDLE = 'CLIENT_PRODUCT_BUNDLE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addProductBundleClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductBundleClient(Container $container): Container
    {
        $container[self::CLIENT_PRODUCT_BUNDLE] = function (Container $container) {
            return $container->getLocator()->productBundle()->client(); // TODO: bridge
        };

        return $container;
    }
}
