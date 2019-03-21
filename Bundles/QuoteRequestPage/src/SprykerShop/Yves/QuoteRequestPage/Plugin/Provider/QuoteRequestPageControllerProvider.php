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
    protected const ROUTE_QUOTE_REQUEST = 'quote-request';
    protected const ROUTE_QUOTE_REQUEST_CREATE = 'quote-request/create';
    protected const ROUTE_QUOTE_REQUEST_REVISE = 'quote-request/revise';
    protected const ROUTE_QUOTE_REQUEST_REVISE_SUBMIT = 'quote-request/revise/submit';
    protected const ROUTE_QUOTE_REQUEST_REVISE_EDIT = 'quote-request/revise/edit';
    protected const ROUTE_QUOTE_REQUEST_REVISE_EDIT_ITEMS = 'quote-request/revise/edit-items';
    protected const ROUTE_QUOTE_REQUEST_CANCEL = 'quote-request/cancel';
    protected const ROUTE_QUOTE_REQUEST_VIEW = 'quote-request/view';
    protected const ROUTE_QUOTE_REQUEST_DETAILS = 'quote-request/details';
    protected const ROUTE_QUOTE_REQUEST_CONVERT_TO_CART = 'quote-request/convert-to-cart';

    protected const PARAM_QUOTE_REQUEST_REFERENCE = 'quoteRequestReference';

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
            ->addQuoteRequestReviseRoute()
            ->addQuoteRequestReviseEditRoute()
            ->addQuoteRequestReviseEditItemsRoute()
            ->addQuoteRequestReviseSubmitRoute()
            ->addQuoteRequestCancelRoute()
            ->addQuoteRequestDetailsRoute()
            ->addQuoteRequestConvertToCartRoute();
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestViewController::indexAction()
     *
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
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestCreateController::createAction()
     *
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
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestReviseController::indexAction()
     *
     * @return $this
     */
    protected function addQuoteRequestReviseRoute()
    {
        $this->createController('/{quoteRequest}/revise/{quoteRequestReference}', static::ROUTE_QUOTE_REQUEST_REVISE, 'QuoteRequestPage', 'QuoteRequestRevise', 'index')
            ->assert('quoteRequest', $this->getAllowedLocalesPattern() . 'quote-request|quote-request')
            ->value('quoteRequest', 'quote-request')
            ->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestReviseController::editAction()
     *
     * @return $this
     */
    protected function addQuoteRequestReviseEditRoute()
    {
        $this->createController('/{quoteRequest}/revise/edit/{quoteRequestReference}', static::ROUTE_QUOTE_REQUEST_REVISE_EDIT, 'QuoteRequestPage', 'QuoteRequestRevise', 'edit')
            ->assert('quoteRequest', $this->getAllowedLocalesPattern() . 'quote-request|quote-request')
            ->value('quoteRequest', 'quote-request')
            ->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestReviseController::editItemsAction()
     *
     * @return $this
     */
    protected function addQuoteRequestReviseEditItemsRoute()
    {
        $this->createController('/{quoteRequest}/revise/edit-items/{quoteRequestReference}', static::ROUTE_QUOTE_REQUEST_REVISE_EDIT_ITEMS, 'QuoteRequestPage', 'QuoteRequestRevise', 'editItems')
            ->assert('quoteRequest', $this->getAllowedLocalesPattern() . 'quote-request|quote-request')
            ->value('quoteRequest', 'quote-request')
            ->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestReviseController::editItemsAction()
     *
     * @return $this
     */
    protected function addQuoteRequestReviseSubmitRoute()
    {
        $this->createController('/{quoteRequest}/revise/submit/{quoteRequestReference}', static::ROUTE_QUOTE_REQUEST_REVISE_SUBMIT, 'QuoteRequestPage', 'QuoteRequestRevise', 'submit')
            ->assert('quoteRequest', $this->getAllowedLocalesPattern() . 'quote-request|quote-request')
            ->value('quoteRequest', 'quote-request')
            ->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestDeleteController::cancelAction()
     *
     * @return $this
     */
    protected function addQuoteRequestCancelRoute()
    {
        $this->createController('/{quoteRequest}/cancel/{quoteRequestReference}', static::ROUTE_QUOTE_REQUEST_CANCEL, 'QuoteRequestPage', 'QuoteRequestDelete', 'cancel')
            ->assert('quoteRequest', $this->getAllowedLocalesPattern() . 'quote-request|quote-request')
            ->value('quoteRequest', 'quote-request')
            ->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestViewController::detailsAction()
     *
     * @return $this
     */
    protected function addQuoteRequestDetailsRoute()
    {
        $this->createController('/{quoteRequest}/details/{quoteRequestReference}', static::ROUTE_QUOTE_REQUEST_DETAILS, 'QuoteRequestPage', 'QuoteRequestView', 'details')
            ->assert('quoteRequest', $this->getAllowedLocalesPattern() . 'quote-request|quote-request')
            ->value('quoteRequest', 'quote-request')
            ->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestCheckoutController::convertToCartAction()
     *
     * @return $this
     */
    protected function addQuoteRequestConvertToCartRoute()
    {
        $this->createController('/{quoteRequest}/convert-to-cart/{quoteRequestReference}', static::ROUTE_QUOTE_REQUEST_CONVERT_TO_CART, 'QuoteRequestPage', 'QuoteRequestCheckout', 'convertToCart')
            ->assert('quoteRequest', $this->getAllowedLocalesPattern() . 'quote-request|quote-request')
            ->value('quoteRequest', 'quote-request')
            ->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }
}
