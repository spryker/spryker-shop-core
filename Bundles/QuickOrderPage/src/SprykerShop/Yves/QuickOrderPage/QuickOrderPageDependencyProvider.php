<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToCartClientBridge;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToQuickOrderClientBridge;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToQuoteClientBridge;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToZedRequestClientBridge;

class QuickOrderPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CART = 'CLIENT_CART';
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';
    public const CLIENT_QUICK_ORDER = 'CLIENT_QUICK_ORDER';
    public const PLUGIN_APPLICATION = 'PLUGIN_APPLICATION';
    public const PLUGINS_QUICK_ORDER_PAGE_WIDGETS = 'PLUGINS_QUICK_ORDER_PAGE_WIDGETS';
    public const PLUGINS_QUICK_ORDER_ITEM_TRANSFER_EXPANDER = 'PLUGINS_QUICK_ORDER_ITEM_TRANSFER_EXPANDER';
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';
    public const PLUGINS_QUICK_ORDER_FORM_HANDLER_STRATEGY = 'PLUGINS_QUICK_ORDER_FORM_HANDLER_STRATEGY';
    public const PLUGINS_QUICK_ORDER_FORM_COLUMN = 'PLUGINS_QUICK_ORDER_FORM_ADDITIONAL_DATA_COLUMN_PROVIDER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addApplication($container);
        $container = $this->addCartClient($container);
        $container = $this->addQuoteClient($container);
        $container = $this->addQuickOrderClient($container);
        $container = $this->addQuickOrderPageWidgetPlugins($container);
        $container = $this->addZedRequestClient($container);
        $container = $this->addQuickOrderItemTransferExpanderPlugins($container);
        $container = $this->addQuickOrderFormHandlerStrategyPlugins($container);
        $container = $this->addQuickOrderFormAdditionalDataColumnProviderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addApplication(Container $container): Container
    {
        $container[static::PLUGIN_APPLICATION] = function () {
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
    protected function addCartClient(Container $container): Container
    {
        $container[static::CLIENT_CART] = function (Container $container) {
            return new QuickOrderPageToCartClientBridge($container->getLocator()->cart()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuoteClient(Container $container): Container
    {
        $container[static::CLIENT_QUOTE] = function (Container $container) {
            return new QuickOrderPageToQuoteClientBridge($container->getLocator()->quote()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuickOrderClient(Container $container): Container
    {
        $container[static::CLIENT_QUICK_ORDER] = function (Container $container) {
            return new QuickOrderPageToQuickOrderClientBridge(
                $container->getLocator()->quickOrder()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuickOrderPageWidgetPlugins(Container $container): Container
    {
        $container[static::PLUGINS_QUICK_ORDER_PAGE_WIDGETS] = function () {
            return $this->getQuickOrderPageWidgetPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuickOrderItemTransferExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_QUICK_ORDER_ITEM_TRANSFER_EXPANDER] = function () {
            return $this->getQuickOrderItemTransferExpanderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addZedRequestClient($container): Container
    {
        $container[static::CLIENT_ZED_REQUEST] = function (Container $container) {
            return new QuickOrderPageToZedRequestClientBridge($container->getLocator()->zedRequest()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuickOrderFormHandlerStrategyPlugins(Container $container): Container
    {
        $container[static::PLUGINS_QUICK_ORDER_FORM_HANDLER_STRATEGY] = function () {
            return $this->getQuickOrderFormHandlerStrategyPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuickOrderFormAdditionalDataColumnProviderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_QUICK_ORDER_FORM_COLUMN] = function () {
            return $this->getQuickOrderFormColumnPlugins();
        };

        return $container;
    }

    /**
     * Returns a list of widget plugin class names that implement
     * Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface.
     *
     * @return string[]
     */
    protected function getQuickOrderPageWidgetPlugins(): array
    {
        return [];
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderItemExpanderPluginInterface[]
     */
    protected function getQuickOrderItemTransferExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFormHandlerStrategyPluginInterface[]
     */
    protected function getQuickOrderFormHandlerStrategyPlugins(): array
    {
        return [];
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFormColumnPluginInterface[]
     */
    protected function getQuickOrderFormColumnPlugins(): array
    {
        return [];
    }
}
