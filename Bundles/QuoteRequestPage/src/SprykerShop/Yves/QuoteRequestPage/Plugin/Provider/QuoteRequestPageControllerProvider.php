<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin` instead.
 */
class QuoteRequestPageControllerProvider extends AbstractYvesControllerProvider
{
    protected const ROUTE_QUOTE_REQUEST = 'quote-request';
    protected const ROUTE_QUOTE_REQUEST_CREATE = 'quote-request/create';
    protected const ROUTE_QUOTE_REQUEST_REVISE = 'quote-request/revise';
    protected const ROUTE_QUOTE_REQUEST_SEND_TO_USER = 'quote-request/send-to-user';
    protected const ROUTE_QUOTE_REQUEST_EDIT = 'quote-request/edit';
    protected const ROUTE_QUOTE_REQUEST_EDIT_ITEMS = 'quote-request/edit-items';
    protected const ROUTE_QUOTE_REQUEST_EDIT_ITEMS_CONFIRM = 'quote-request/edit-items-confirm';
    protected const ROUTE_QUOTE_REQUEST_CANCEL = 'quote-request/cancel';
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
            ->addQuoteRequestEditRoute()
            ->addQuoteRequestEditItemsRoute()
            ->addQuoteRequestEditItemsConfirmRoute()
            ->addQuoteRequestSendToUserRoute()
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
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestEditController::indexAction()
     *
     * @return $this
     */
    protected function addQuoteRequestEditRoute()
    {
        $this->createController('/{quoteRequest}/edit/{quoteRequestReference}', static::ROUTE_QUOTE_REQUEST_EDIT, 'QuoteRequestPage', 'QuoteRequestEdit', 'index')
            ->assert('quoteRequest', $this->getAllowedLocalesPattern() . 'quote-request|quote-request')
            ->value('quoteRequest', 'quote-request')
            ->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestEditItemsController::indexAction()
     *
     * @return $this
     */
    protected function addQuoteRequestEditItemsRoute()
    {
        $this->createController('/{quoteRequest}/edit-items/{quoteRequestReference}', static::ROUTE_QUOTE_REQUEST_EDIT_ITEMS, 'QuoteRequestPage', 'QuoteRequestEditItems', 'index')
            ->assert('quoteRequest', $this->getAllowedLocalesPattern() . 'quote-request|quote-request')
            ->value('quoteRequest', 'quote-request')
            ->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestEditItemsController::indexAction()
     *
     * @return $this
     */
    protected function addQuoteRequestEditItemsConfirmRoute()
    {
        $this->createController('/{quoteRequest}/edit-items-confirm/{quoteRequestReference}', static::ROUTE_QUOTE_REQUEST_EDIT_ITEMS_CONFIRM, 'QuoteRequestPage', 'QuoteRequestEditItems', 'confirm')
            ->assert('quoteRequest', $this->getAllowedLocalesPattern() . 'quote-request|quote-request')
            ->value('quoteRequest', 'quote-request')
            ->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestEditController::submitAction()
     *
     * @return $this
     */
    protected function addQuoteRequestSendToUserRoute()
    {
        $this->createController('/{quoteRequest}/send-to-user/{quoteRequestReference}', static::ROUTE_QUOTE_REQUEST_SEND_TO_USER, 'QuoteRequestPage', 'QuoteRequestEdit', 'sendToUser')
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
