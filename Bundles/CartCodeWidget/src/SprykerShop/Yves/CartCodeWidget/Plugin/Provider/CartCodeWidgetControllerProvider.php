<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartCodeWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\CartCodeWidget\Plugin\Router\CartCodeWidgetRouteProviderPlugin` instead.
 */
class CartCodeWidgetControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_CART_CODE_ADD = 'cart-code/code/add';
    public const ROUTE_CART_CODE_REMOVE = 'cart-code/code/remove';
    public const ROUTE_CART_CODE_CLEAR = 'cart-code/code/clear';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->addAddCodeRoute()
            ->addRemoveCodeRoute()
            ->addClearCodeRoute();
    }

    /**
     * @return $this
     */
    protected function addAddCodeRoute()
    {
        $this->createController('/{cartCode}/code/add', self::ROUTE_CART_CODE_ADD, 'CartCodeWidget', 'Code', 'add')
            ->assert('cartCode', $this->getAllowedLocalesPattern() . 'cart-code|cart-code')
            ->value('cartCode', 'cart-code');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addRemoveCodeRoute()
    {
        $this->createController('/{cartCode}/code/remove', self::ROUTE_CART_CODE_REMOVE, 'CartCodeWidget', 'Code', 'remove')
            ->assert('cartCode', $this->getAllowedLocalesPattern() . 'cart-code|cart-code')
            ->value('cartCode', 'cart-code');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addClearCodeRoute()
    {
        $this->createController('/{cartCode}/code/clear', self::ROUTE_CART_CODE_CLEAR, 'CartCodeWidget', 'Code', 'clear')
            ->assert('cartCode', $this->getAllowedLocalesPattern() . 'cart-code|cart-code')
            ->value('cartCode', 'cart-code');

        return $this;
    }
}
