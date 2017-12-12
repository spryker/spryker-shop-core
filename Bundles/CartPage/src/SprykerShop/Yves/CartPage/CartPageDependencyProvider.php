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
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientBridge;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToProductClientBridge;

class CartPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CART = 'CLIENT_CART';
    public const CLIENT_PRODUCT = 'CLIENT_PRODUCT';
    public const PLUGIN_APPLICATION = 'PLUGIN_APPLICATION';
    public const PLUGIN_CART_VARIANT = 'PLUGIN_CART_VARIANT';
    public const PLUGIN_CART_ITEM_TRANSFORMERS = 'PLUGIN_CART_ITEM_TRANSFORMERS';
    public const PLUGIN_CART_PAGE_WIDGETS = 'PLUGIN_CART_PAGE_WIDGETS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addCartClient($container);
        $container = $this->addProductClient($container);
        $container = $this->addApplication($container);
        $container = $this->addCartVariantAttributeMapperPlugin($container);
        $container = $this->addCartPageWidgetPlugins($container);
        $container = $this->addCartItemTransformerPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartClient(Container $container): Container
    {
        $container[self::CLIENT_CART] = function (Container $container) {
            return new CartPageToCartClientBridge($container->getLocator()->cart()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductClient(Container $container): Container
    {
        $container[static::CLIENT_PRODUCT] = function (Container $container) {
            return new CartPageToProductClientBridge($container->getLocator()->product()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addApplication(Container $container): Container
    {
        $container[self::PLUGIN_APPLICATION] = function () {
            $pimplePlugin = new Pimple();

            return $pimplePlugin->getApplication();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartVariantAttributeMapperPlugin(Container $container): Container
    {
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
