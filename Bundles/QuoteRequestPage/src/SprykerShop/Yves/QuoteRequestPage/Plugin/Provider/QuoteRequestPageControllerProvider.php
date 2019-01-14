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
    public const ROUTE_QUOTE_REQUEST_DETAILS = 'quote-request/details';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addQuoteRequestRoute()
            ->addQuoteRequestDetailsRoute();
    }

    /**
     * @return $this
     */
    protected function addQuoteRequestRoute(): self
    {
        $this->createController('/{quoteRequest}', static::ROUTE_QUOTE_REQUEST, 'QuoteRequestPage', 'QuoteRequestOverview')
            ->assert('quoteRequest', $this->getAllowedLocalesPattern() . 'quote-request|quote-request')
            ->value('quoteRequest', 'quote-request');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addQuoteRequestDetailsRoute(): self
    {
        $this->createGetController('/{quoteRequest}/details/{quoteRequestReference}', static::ROUTE_QUOTE_REQUEST_DETAILS, 'QuoteRequestPage', 'QuoteRequest')
            ->assert('quoteRequest', $this->getAllowedLocalesPattern() . 'quote-request|quote-request')
            ->value('quoteRequest', 'quote-request')
            ->assert('quoteRequestReference', '.+');

        return $this;
    }
}
