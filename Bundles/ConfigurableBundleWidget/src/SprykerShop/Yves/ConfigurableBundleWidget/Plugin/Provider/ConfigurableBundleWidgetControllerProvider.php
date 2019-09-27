<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `SprykerShop\Yves\ConfigurableBundleWidget\Plugin\Router\ConfigurableBundleWidgetRouteProviderPlugin` instead.
 */
class ConfigurableBundleWidgetControllerProvider extends AbstractYvesControllerProvider
{
    protected const ROUTE_CART_CONFIGURED_BUNDLE_REMOVE = 'cart/configured-bundle/remove';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addCartConfiguredBundleRemoveRoute();
    }

    /**
     * @uses \SprykerShop\Yves\ConfigurableBundleWidget\Controller\CartController::removeConfiguredBundleAction()
     *
     * @return $this
     */
    protected function addCartConfiguredBundleRemoveRoute()
    {
        $this->createController('/{cart}/configured-bundle/remove/{configuredBundleGroupKey}', static::ROUTE_CART_CONFIGURED_BUNDLE_REMOVE, 'ConfigurableBundleWidget', 'Cart', 'removeConfiguredBundle')
            ->assert('cart', $this->getAllowedLocalesPattern() . 'cart|cart')
            ->value('cart', 'cart')
            ->value('configuredBundleGroupKey', '');

        return $this;
    }
}
