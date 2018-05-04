<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class SharedCartPageControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_SHARED_CART_SHARE = 'shared-cart/share';
    public const ROUTE_SHARED_CART_UNSHARE = 'shared-cart/unshare';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->addShareController($allowedLocalesPattern)
            ->addUnshareController($allowedLocalesPattern);
    }

    /**
     * @param string $allowedLocalesPattern
     *
     * @return $this
     */
    protected function addShareController($allowedLocalesPattern): self
    {
        $this->createController('/{sharedCart}/share/{idQuote}', static::ROUTE_SHARED_CART_SHARE, 'SharedCartPage', 'Share', 'index')
            ->assert('sharedCart', $allowedLocalesPattern . 'shared-cart|shared-cart')
            ->value('sharedCart', 'shared-cart');

        return $this;
    }

    /**
     * @param string $allowedLocalesPattern
     *
     * @return $this
     */
    protected function addUnshareController($allowedLocalesPattern): self
    {
        $this->createController('/{sharedCart}/unshare/{idQuote}/{idCompanyUser}/{idPermissionGroup}', static::ROUTE_SHARED_CART_UNSHARE, 'SharedCartPage', 'Unshare', 'index')
            ->assert('sharedCart', $allowedLocalesPattern . 'shared-cart|shared-cart')
            ->value('sharedCart', 'shared-cart');

        return $this;
    }
}
