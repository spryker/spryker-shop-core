<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class MultiCartPageControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_MULTI_CART_CREATE = 'multi-cart/create';
    public const ROUTE_MULTI_CART_UPDATE = 'multi-cart/update';
    public const ROUTE_MULTI_CART_DELETE = 'multi-cart/delete';
    public const ROUTE_MULTI_CART_SET_DEFAULT = 'multi-cart/set-default';
    public const ROUTE_MULTI_CART_CLEAR = 'multi-cart/clear';
    public const ROUTE_MULTI_CART_DUPLICATE = 'multi-cart/duplicate';

    public const PARAM_ID_QUOTE = 'idQuote';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->addMultiCartCreateRoute($allowedLocalesPattern)
            ->addMultiCartUpdateRoute($allowedLocalesPattern)
            ->addMultiCartDeleteRoute($allowedLocalesPattern)
            ->addMultiCartClearRoute($allowedLocalesPattern)
            ->addMultiCartDuplicateRoute($allowedLocalesPattern)
            ->addMultiCartSetDefaultRoute($allowedLocalesPattern);
    }

    /**
     * @param string $allowedLocalesPattern
     *
     * @return $this
     */
    protected function addMultiCartCreateRoute($allowedLocalesPattern): self
    {
        $this->createController('/{multiCart}/create', static::ROUTE_MULTI_CART_CREATE, 'MultiCartPage', 'MultiCart', 'create')
            ->assert('multiCart', $allowedLocalesPattern . 'multi-cart|multi-cart')
            ->value('multiCart', 'multi-cart');

        return $this;
    }

    /**
     * @param string $allowedLocalesPattern
     *
     * @return $this
     */
    protected function addMultiCartUpdateRoute($allowedLocalesPattern): self
    {
        $this->createController('/{multiCart}/update/{idQuote}', static::ROUTE_MULTI_CART_UPDATE, 'MultiCartPage', 'MultiCart', 'update')
            ->assert('multiCart', $allowedLocalesPattern . 'multi-cart|multi-cart')
            ->assert(self::PARAM_ID_QUOTE, '\d+')
            ->value('multiCart', 'multi-cart');

        return $this;
    }

    /**
     * @param string $allowedLocalesPattern
     *
     * @return $this
     */
    protected function addMultiCartDeleteRoute($allowedLocalesPattern): self
    {
        $this->createGetController('/{multiCart}/delete/{idQuote}', static::ROUTE_MULTI_CART_DELETE, 'MultiCartPage', 'MultiCart', 'delete')
            ->assert('multiCart', $allowedLocalesPattern . 'multi-cart|multi-cart')
            ->assert(self::PARAM_ID_QUOTE, '\d+')
            ->value('multiCart', 'multi-cart');

        return $this;
    }

    /**
     * @param string $allowedLocalesPattern
     *
     * @return $this
     */
    protected function addMultiCartClearRoute($allowedLocalesPattern): self
    {
        $this->createGetController('/{multiCart}/clear/{idQuote}', static::ROUTE_MULTI_CART_CLEAR, 'MultiCartPage', 'MultiCart', 'clear')
            ->assert('multiCart', $allowedLocalesPattern . 'multi-cart|multi-cart')
            ->assert(self::PARAM_ID_QUOTE, '\d+')
            ->value('multiCart', 'multi-cart');

        return $this;
    }

    /**
     * @param string $allowedLocalesPattern
     *
     * @return $this
     */
    protected function addMultiCartDuplicateRoute($allowedLocalesPattern): self
    {
        $this->createGetController('/{multiCart}/duplicate/{idQuote}', static::ROUTE_MULTI_CART_DUPLICATE, 'MultiCartPage', 'MultiCart', 'duplicate')
            ->assert('multiCart', $allowedLocalesPattern . 'multi-cart|multi-cart')
            ->assert(self::PARAM_ID_QUOTE, '\d+')
            ->value('multiCart', 'multi-cart');

        return $this;
    }

    /**
     * @param string $allowedLocalesPattern
     *
     * @return $this
     */
    protected function addMultiCartSetDefaultRoute($allowedLocalesPattern): self
    {
        $this->createGetController('/{multiCart}/set-default/{idQuote}', static::ROUTE_MULTI_CART_SET_DEFAULT, 'MultiCartPage', 'MultiCart', 'setDefault')
            ->assert('multiCart', $allowedLocalesPattern . 'multi-cart|multi-cart')
            ->assert(self::PARAM_ID_QUOTE, '\d+')
            ->value('multiCart', 'multi-cart');

        return $this;
    }
}
