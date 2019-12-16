<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartShareWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\PersistentCartShareWidget\Plugin\Router\PersistentCartShareWidgetRouteProviderPlugin` instead.
 */
class ShareCartByLinkWidgetControllerProvider extends AbstractYvesControllerProvider
{
    public const CART_CREATE_LINK = 'cart/create-link';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addCreateLinkRoute();
    }

    /**
     * @uses \SprykerShop\Yves\PersistentCartShareWidget\Controller\PersistentCartShareWidgetController::indexAction()
     *
     * @return $this
     */
    protected function addCreateLinkRoute()
    {
        $this->createController('/{PersistentCartShareWidget}/create-link/{idQuote}/{shareOptionGroup}', static::CART_CREATE_LINK, 'PersistentCartShareWidget', 'PersistentCartShareWidget')
            ->assert('PersistentCartShareWidget', $this->getAllowedLocalesPattern() . 'cart|cart')
            ->value('PersistentCartShareWidget', 'cart')
            ->assert('idQuote', '\d+');

        return $this;
    }
}
