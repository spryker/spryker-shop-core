<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Client\CartPage;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use SprykerShop\Client\ProductBundleWidget\Plugin\ProductBundleCartItemTransformerPlugin;

class CartPageDependencyProvider extends AbstractDependencyProvider
{
    public const PLUGIN_CART_ITEM_TRANSFORMERS = 'PLUGIN_CART_ITEM_TRANSFORMERS';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $this->addCartItemTransformerPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCartItemTransformerPlugins(Container $container)
    {
        $container[static::PLUGIN_CART_ITEM_TRANSFORMERS] = function (Container $container) {
            return $this->getCartItemTransformerPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \SprykerShop\Client\CartPage\Dependency\Plugin\CartItemTransformerPluginInterface[]
     */
    protected function getCartItemTransformerPlugins(Container $container)
    {
        return [
            // TODO: move to project level
            new ProductBundleCartItemTransformerPlugin(),
        ];
    }
}
