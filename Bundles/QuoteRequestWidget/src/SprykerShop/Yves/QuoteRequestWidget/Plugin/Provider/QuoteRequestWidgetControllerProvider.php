<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\QuoteRequestWidget\Plugin\Router\QuoteRequestWidgetRouteProviderPlugin` instead.
 */
class QuoteRequestWidgetControllerProvider extends AbstractYvesControllerProvider
{
    protected const ROUTE_QUOTE_REQUEST_SAVE_CART = 'quote-request/cart/save';
    protected const ROUTE_QUOTE_REQUEST_CLEAR_CART = 'quote-request/cart/clear';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addQuoteRequestSaveCartRoute()
            ->addQuoteRequestClearCartRoute();
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestWidget\Controller\QuoteRequestCartController::indexAction()
     *
     * @return $this
     */
    protected function addQuoteRequestSaveCartRoute()
    {
        $this->createController('/{quoteRequest}/cart/save', static::ROUTE_QUOTE_REQUEST_SAVE_CART, 'QuoteRequestWidget', 'QuoteRequestCart', 'save')
            ->assert('quote-request', $this->getAllowedLocalesPattern() . 'quote-request|quote-request')
            ->value('quoteRequest', 'quote-request');

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestWidget\Controller\QuoteRequestCartController::clearAction()
     *
     * @return $this
     */
    protected function addQuoteRequestClearCartRoute()
    {
        $this->createController('/{quoteRequest}/cart/clear', static::ROUTE_QUOTE_REQUEST_CLEAR_CART, 'QuoteRequestWidget', 'QuoteRequestCart', 'clear')
            ->assert('quote-request', $this->getAllowedLocalesPattern() . 'quote-request|quote-request')
            ->value('quoteRequest', 'quote-request');

        return $this;
    }
}
