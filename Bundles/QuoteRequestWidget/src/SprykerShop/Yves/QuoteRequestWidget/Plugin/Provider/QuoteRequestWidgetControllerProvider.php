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
    public const ROUTE_CREATE_QUOTE_REQUEST = 'quote-request/create-quote-request';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addAddItemRoute();
    }

    /**
     * @return $this
     */
    protected function addAddItemRoute(): self
    {
        $this->createController('/{quoteRequest}/create-quote-request', static::ROUTE_CREATE_QUOTE_REQUEST, 'QuoteRequestWidget', 'QuoteRequestWidget')
            ->assert('quoteRequest', $this->getAllowedLocalesPattern() . 'quote-request|quote-request')
            ->value('quoteRequest', 'quote-request');

        return $this;
    }
}
