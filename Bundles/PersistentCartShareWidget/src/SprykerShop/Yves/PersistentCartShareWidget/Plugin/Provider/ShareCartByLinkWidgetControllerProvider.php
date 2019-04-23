<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartShareWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

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
     * @return $this
     */
    protected function addCreateLinkRoute()
    {
        $this->createController('/{PersistentCartShareWidget}/create-link/{idQuote}/{permissionOption}', static::CART_CREATE_LINK, 'PersistentCartShareWidget', 'PersistentCartShareWidget')
            ->assert('PersistentCartShareWidget', $this->getAllowedLocalesPattern() . 'cart|cart')
            ->value('PersistentCartShareWidget', 'cart')
            ->assert('idQuote', '\d+');

        return $this;
    }
}
