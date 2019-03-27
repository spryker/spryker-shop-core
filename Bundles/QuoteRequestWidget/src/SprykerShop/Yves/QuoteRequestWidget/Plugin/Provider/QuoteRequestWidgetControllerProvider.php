<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class QuoteRequestWidgetControllerProvider extends AbstractYvesControllerProvider
{
    protected const ROUTE_QUOTE_REQUEST_CART = 'quote-request/cart';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addQuoteRequestCartRoute();
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestWidget\Controller\QuoteRequestCartController::indexAction()
     *
     * @return $this
     */
    protected function addQuoteRequestCartRoute()
    {
        $this->createController('/{quoteRequest}/cart', static::ROUTE_QUOTE_REQUEST_CART, 'QuoteRequestWidget', 'QuoteRequestCart', 'index')
            ->assert('quote-request', $this->getAllowedLocalesPattern() . 'quote-request|quote-request')
            ->value('quoteRequest', 'quote-request');

        return $this;
    }
}
