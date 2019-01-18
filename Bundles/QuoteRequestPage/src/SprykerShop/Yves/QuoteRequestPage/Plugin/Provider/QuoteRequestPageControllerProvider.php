<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class QuoteRequestPageControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_QUOTE_REQUEST = 'quote-request';
    public const ROUTE_QUOTE_REQUEST_CREATE = 'quote-request/create';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addQuoteRequestRoute()
            ->addQuoteRequestCreateRoute();
    }

    /**
     * @return $this
     */
    protected function addQuoteRequestRoute(): self
    {
        $this->createController('/{quoteRequest}', static::ROUTE_QUOTE_REQUEST, 'QuoteRequestPage', 'View')
            ->assert('quoteRequest', $this->getAllowedLocalesPattern() . 'quote-request|quote-request')
            ->value('quoteRequest', 'quote-request');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addQuoteRequestCreateRoute(): self
    {
        $this->createController('/{quoteRequest}/create', static::ROUTE_QUOTE_REQUEST_CREATE, 'QuoteRequestPage', 'Create', 'create')
            ->assert('quoteRequest', $this->getAllowedLocalesPattern() . 'quote-request|quote-request')
            ->value('quoteRequest', 'quote-request');

        return $this;
    }
}
