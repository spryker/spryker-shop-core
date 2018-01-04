<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CheckoutPage\Plugin\CheckoutBreadcrumbPlugin;

class CheckoutWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    const PLUGIN_CHECKOUT_BREADCRUMB = 'PLUGIN_CHECKOUT_BREADCRUMB';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addCheckoutBreadcrumbPluginPlugins($container);

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
}
