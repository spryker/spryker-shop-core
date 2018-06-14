<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\WishlistWidget\Dependency\Client\WishlistWidgetToWishlistClientBridge;

class WishlistWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_WISHLIST = 'CLIENT_WISHLIST';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addWishlistClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addWishlistClient(Container $container): Container
    {
        $container[static::CLIENT_WISHLIST] = function (Container $container) {
            return new WishlistWidgetToWishlistClientBridge($container->getLocator()->wishlist()->client());
        };

        return $container;
    }
}
