<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNoteWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CartNoteWidget\Dependency\Client\CartNoteWidgetToCartNoteClientBridge;

class CartNoteWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CART_NOTE = 'CLIENT_CART_NOTE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addCartNoteClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartNoteClient(Container $container): Container
    {
        $container[static::CLIENT_CART_NOTE] = function (Container $container) {
            return new CartNoteWidgetToCartNoteClientBridge($container->getLocator()->cartNote()->client());
        };

        return $container;
    }
}
