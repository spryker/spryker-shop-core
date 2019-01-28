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
    public const ROUTE_QUOTE_REQUEST_CANCEL = 'quote-request/cancel';
    public const ROUTE_QUOTE_REQUEST_VIEW = 'quote-request/view';
    public const ROUTE_QUOTE_REQUEST_DETAILS = 'quote-request/details';

    protected const QUOTE_REQUEST_REFERENCE_REGEX = '[a-zA-Z0-9-]+';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addQuoteRequestRoute()
            ->addQuoteRequestCreateRoute()
            ->addQuoteRequestCancelRoute()
            ->addQuoteRequestDetailsRoute();
    }

    /**
     * @return $this
     */
    protected function addQuoteRequestRoute()
    {
        $this->createController('/{quoteRequest}', static::ROUTE_QUOTE_REQUEST, 'QuoteRequestPage', 'QuoteRequestView')
            ->assert('quoteRequest', $this->getAllowedLocalesPattern() . 'quote-request|quote-request')
            ->value('quoteRequest', 'quote-request');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addQuoteRequestCreateRoute()
    {
        $this->createController('/{quoteRequest}/create', static::ROUTE_QUOTE_REQUEST_CREATE, 'QuoteRequestPage', 'QuoteRequestCreate', 'create')
            ->assert('quoteRequest', $this->getAllowedLocalesPattern() . 'quote-request|quote-request')
            ->value('quoteRequest', 'quote-request');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addQuoteRequestCancelRoute()
    {
        $this->createController('/{quoteRequest}/cancel/{quoteRequestReference}', static::ROUTE_QUOTE_REQUEST_CANCEL, 'QuoteRequestPage', 'QuoteRequestDelete', 'cancel')
            ->assert('quoteRequest', $this->getAllowedLocalesPattern() . 'quote-request|quote-request')
            ->value('quoteRequest', 'quote-request')
            ->assert('quoteRequestReference', static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addQuoteRequestDetailsRoute()
    {
        $this->createController('/{quoteRequest}/details/{quoteRequestReference}', static::ROUTE_QUOTE_REQUEST_DETAILS, 'QuoteRequestPage', 'QuoteRequestView', 'details')
            ->assert('quoteRequest', $this->getAllowedLocalesPattern() . 'quote-request|quote-request')
            ->value('quoteRequest', 'quote-request')
            ->assert('quoteRequestReference', static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }
}
