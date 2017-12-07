<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CartPage;

use Spryker\Yves\CartVariant\Dependency\Plugin\CartVariantAttributeMapperPlugin;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;

class CartPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGIN_CART_ITEM_TRANSFORMERS = 'PLUGIN_CART_ITEM_TRANSFORMERS';
    public const CLIENT_CALCULATION = 'calculation client';
    public const CLIENT_CART = 'cart client';
    public const PLUGIN_APPLICATION = 'application plugin';
    public const PLUGIN_CART_VARIANT = 'PLUGIN_CART_VARIANT';
    public const CLIENT_PRODUCT = 'CLIENT_PRODUCT';
    public const PLUGIN_CART_PAGE_WIDGETS = 'PLUGIN_CART_PAGE_WIDGETS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->provideClients($container);
        $container = $this->providePlugins($container);
        $container = $this->addCartPageWidgetPlugins($container);
        $container = $this->addCartItemTransformerPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function provideClients(Container $container)
    {
        $container[self::CLIENT_CALCULATION] = function (Container $container) {
            return $container->getLocator()->calculation()->client();
        };

        $container[self::CLIENT_CART] = function (Container $container) {
            return $container->getLocator()->cart()->client();
        };

        $container[static::CLIENT_PRODUCT] = function (Container $container) {
            return $container->getLocator()->product()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function providePlugins(Container $container)
    {
        $container[self::PLUGIN_APPLICATION] = function () {
            $pimplePlugin = new Pimple();

            return $pimplePlugin->getApplication();
        };

        $container[self::PLUGIN_CART_VARIANT] = function () {
            return new CartVariantAttributeMapperPlugin();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartPageWidgetPlugins(Container $container)
    {
        $container[self::PLUGIN_CART_PAGE_WIDGETS] = function () {
            return $this->getCartPageWidgetPlugins();
        };

        return $container;
    }

    /**
     * Returns a list of widget plugin class names that implement \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface.
     *
     * @return string[]
     */
    protected function getCartPageWidgetPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartItemTransformerPlugins(Container $container)
    {
        $container[static::PLUGIN_CART_ITEM_TRANSFORMERS] = function () {
            return $this->getCartItemTransformerPlugins();
        };

        return $container;
    }

    /**
     * @return \SprykerShop\Yves\CartPage\Dependency\Plugin\CartItemTransformerPluginInterface[]
     */
    protected function getCartItemTransformerPlugins(): array
    {
        return [];
    }
}
