<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartReorderPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CartReorderPage\Dependency\Client\CartReorderPageToCartReorderClientBridge;
use SprykerShop\Yves\CartReorderPage\Dependency\Client\CartReorderPageToCustomerClientBridge;
use SprykerShop\Yves\CartReorderPage\Dependency\Client\CartReorderPageToZedRequestClientBridge;

class CartReorderPageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';

    /**
     * @var string
     */
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    /**
     * @var string
     */
    public const CLIENT_CART_REORDER = 'CLIENT_CART_REORDER';

    /**
     * @var string
     */
    public const PLUGINS_CART_REORDER_ITEM_CHECKBOX_ATTRIBUTE_EXPANDER = 'PLUGINS_CART_REORDER_ITEM_CHECKBOX_ATTRIBUTE_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_CART_REORDER_REQUEST_EXPANDER = 'PLUGINS_CART_REORDER_REQUEST_EXPANDER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addCustomerClient($container);
        $container = $this->addZedRequestClient($container);
        $container = $this->addCartReorderClient($container);
        $container = $this->addCartReorderItemCheckboxAttributeExpanderPlugins($container);
        $container = $this->addCartReorderRequestExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container->set(static::CLIENT_CUSTOMER, function (Container $container) {
            return new CartReorderPageToCustomerClientBridge(
                $container->getLocator()->customer()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addZedRequestClient(Container $container): Container
    {
        $container->set(static::CLIENT_ZED_REQUEST, function (Container $container) {
            return new CartReorderPageToZedRequestClientBridge(
                $container->getLocator()->zedRequest()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartReorderClient(Container $container): Container
    {
        $container->set(static::CLIENT_CART_REORDER, function (Container $container) {
            return new CartReorderPageToCartReorderClientBridge(
                $container->getLocator()->cartReorder()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartReorderItemCheckboxAttributeExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CART_REORDER_ITEM_CHECKBOX_ATTRIBUTE_EXPANDER, function () {
            return $this->getCartReorderItemCheckboxAttributeExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return list<\SprykerShop\Yves\CartReorderPageExtension\Dependency\Plugin\CartReorderItemCheckboxAttributeExpanderPluginInterface>
     */
    protected function getCartReorderItemCheckboxAttributeExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartReorderRequestExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CART_REORDER_REQUEST_EXPANDER, function () {
            return $this->getCartReorderRequestExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return list<\SprykerShop\Yves\CartReorderPageExtension\Dependency\Plugin\CartReorderRequestExpanderPluginInterface>
     */
    protected function getCartReorderRequestExpanderPlugins(): array
    {
        return [];
    }
}
