<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartPage\Plugin\Router;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class SharedCartPageRouteProviderPlugin extends \Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin
{
    public const ROUTE_SHARED_CART_SHARE = 'shared-cart/share';
    public const ROUTE_SHARED_CART_DISMISS = 'shared-cart/dismiss';
    public const ROUTE_SHARED_CART_DISMISS_CONFIRM = 'shared-cart/dismiss-confirm';

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    public function addRoutes(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $this->addShareController()
            ->addDismissController()
            ->addDismissConfirmController();
        return $routeCollection;
    }

    /**
     * @return $this
     */
    protected function addShareController()
    {
        $this->createController('/{sharedCart}/share/{idQuote}', static::ROUTE_SHARED_CART_SHARE, 'SharedCartPage', 'Share', 'index')
            ->assert('sharedCart', $this->getAllowedLocalesPattern() . 'shared-cart|shared-cart')
            ->value('sharedCart', 'shared-cart');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addDismissController()
    {
        $this->createController('/{sharedCart}/dismiss/{idQuote}', static::ROUTE_SHARED_CART_DISMISS, 'SharedCartPage', 'Dismiss', 'index')
            ->assert('sharedCart', $this->getAllowedLocalesPattern() . 'shared-cart|shared-cart')
            ->value('sharedCart', 'shared-cart');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addDismissConfirmController()
    {
        $this->createController('/{sharedCart}/dismiss-confirm/{idQuote}', static::ROUTE_SHARED_CART_DISMISS_CONFIRM, 'SharedCartPage', 'Dismiss', 'Confirm')
            ->assert('sharedCart', $this->getAllowedLocalesPattern() . 'shared-cart|shared-cart')
            ->value('sharedCart', 'shared-cart');

        return $this;
    }
}
