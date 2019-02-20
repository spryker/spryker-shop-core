<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CheckoutPage\Plugin\CheckoutBreadcrumbPlugin;
use SprykerShop\Yves\CheckoutWidget\Dependency\Client\CheckoutWidgetToCheckoutClientBridge;

class CheckoutWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGIN_CHECKOUT_BREADCRUMB = 'PLUGIN_CHECKOUT_BREADCRUMB';
    public const CLIENT_CHECKOUT = 'CLIENT_CHECKOUT';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addCheckoutBreadcrumbPluginPlugins($container);
        $container = $this->addCheckoutClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCheckoutBreadcrumbPluginPlugins(Container $container)
    {
        $container[self::PLUGIN_CHECKOUT_BREADCRUMB] = function () {
            return new CheckoutBreadcrumbPlugin();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCheckoutClient(Container $container)
    {
        $container[static::CLIENT_CHECKOUT] = function (Container $container) {
            return new CheckoutWidgetToCheckoutClientBridge($container->getLocator()->checkout()->client());
        };

        return $container;
    }
}
