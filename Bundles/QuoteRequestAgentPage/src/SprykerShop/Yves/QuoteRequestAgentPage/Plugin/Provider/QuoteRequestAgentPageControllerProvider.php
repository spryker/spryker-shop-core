<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin` instead.
 */
class QuoteRequestAgentPageControllerProvider extends AbstractYvesControllerProvider
{
    protected const ROUTE_QUOTE_REQUEST_AGENT = 'agent/quote-request';
    protected const ROUTE_QUOTE_REQUEST_AGENT_CANCEL = 'agent/quote-request/cancel';
    protected const ROUTE_QUOTE_REQUEST_AGENT_DETAILS = 'agent/quote-request/details';
    protected const ROUTE_QUOTE_REQUEST_AGENT_EDIT = 'agent/quote-request/edit';
    protected const ROUTE_QUOTE_REQUEST_AGENT_CREATE = 'agent/quote-request/create';
    protected const ROUTE_QUOTE_REQUEST_AGENT_SEND_TO_CUSTOMER = 'agent/quote-request/send-to-customer';
    protected const ROUTE_QUOTE_REQUEST_AGENT_EDIT_ITEMS = 'agent/quote-request/edit-items';
    protected const ROUTE_QUOTE_REQUEST_AGENT_EDIT_ITEMS_CONFIRM = 'agent/quote-request/edit-items-confirm';
    protected const ROUTE_QUOTE_REQUEST_AGENT_REVISE = 'agent/quote-request/revise';
    protected const ROUTE_QUOTE_REQUEST_AGENT_CONVERT_TO_CART = 'agent/quote-request/convert-to-cart';

    protected const PARAM_QUOTE_REQUEST_REFERENCE = 'quoteRequestReference';

    protected const QUOTE_REQUEST_REFERENCE_REGEX = '[a-zA-Z0-9-]+';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addQuoteRequestAgentRoute()
            ->addQuoteRequestAgentCancelRoute()
            ->addQuoteRequestDetailsRoute()
            ->addQuoteRequestReviseRoute()
            ->addQuoteRequestEditRoute()
            ->addQuoteRequestSendToCustomerRoute()
            ->addQuoteRequestEditItemsRoute()
            ->addQuoteRequestEditItemsConfirmRoute()
            ->addQuoteRequestCreateRoute()
            ->addQuoteRequestConvertToCartRoute();
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Controller\QuoteRequestAgentViewController::indexAction()
     *
     * @return $this
     */
    protected function addQuoteRequestAgentRoute()
    {
        $this->createController('/{agent}/quote-request', static::ROUTE_QUOTE_REQUEST_AGENT, 'QuoteRequestAgentPage', 'QuoteRequestAgentView')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent');

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Controller\QuoteRequestAgentDeleteController::cancelAction()
     *
     * @return $this
     */
    protected function addQuoteRequestAgentCancelRoute()
    {
        $this->createController('/{agent}/quote-request/cancel/{quoteRequestReference}', static::ROUTE_QUOTE_REQUEST_AGENT_CANCEL, 'QuoteRequestAgentPage', 'QuoteRequestAgentDelete', 'cancel')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent')
            ->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Controller\QuoteRequestAgentViewController::detailsAction()
     *
     * @return $this
     */
    protected function addQuoteRequestDetailsRoute()
    {
        $this->createController('/{agent}/quote-request/details/{quoteRequestReference}', static::ROUTE_QUOTE_REQUEST_AGENT_DETAILS, 'QuoteRequestAgentPage', 'QuoteRequestAgentView', 'details')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent')
            ->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Controller\QuoteRequestAgentReviseController::indexAction()
     *
     * @return $this
     */
    protected function addQuoteRequestReviseRoute()
    {
        $this->createController('/{agent}/quote-request/revise/{quoteRequestReference}', static::ROUTE_QUOTE_REQUEST_AGENT_REVISE, 'QuoteRequestAgentPage', 'QuoteRequestAgentRevise', 'index')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent')
            ->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Controller\QuoteRequestAgentEditController::editAction()
     *
     * @return $this
     */
    protected function addQuoteRequestEditRoute()
    {
        $this->createController('/{agent}/quote-request/edit/{quoteRequestReference}', static::ROUTE_QUOTE_REQUEST_AGENT_EDIT, 'QuoteRequestAgentPage', 'QuoteRequestAgentEdit', 'edit')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent')
            ->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Controller\QuoteRequestAgentCreateController::createAction()
     *
     * @return $this
     */
    protected function addQuoteRequestCreateRoute()
    {
        $this->createController('/{agent}/quote-request/create', static::ROUTE_QUOTE_REQUEST_AGENT_CREATE, 'QuoteRequestAgentPage', 'QuoteRequestAgentCreate', 'create')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent');

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Controller\QuoteRequestAgentEditController::sendToCustomerAction()
     *
     * @return $this
     */
    protected function addQuoteRequestSendToCustomerRoute()
    {
        $this->createController('/{agent}/quote-request/send-to-customer/{quoteRequestReference}', static::ROUTE_QUOTE_REQUEST_AGENT_SEND_TO_CUSTOMER, 'QuoteRequestAgentPage', 'QuoteRequestAgentEdit', 'sendToCustomer')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent')
            ->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Controller\QuoteRequestAgentEditItemsController::indexAction()
     *
     * @return $this
     */
    protected function addQuoteRequestEditItemsRoute()
    {
        $this->createController('/{agent}/quote-request/edit-items/{quoteRequestReference}', static::ROUTE_QUOTE_REQUEST_AGENT_EDIT_ITEMS, 'QuoteRequestAgentPage', 'QuoteRequestAgentEditItems', 'index')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent')
            ->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Controller\QuoteRequestAgentEditItemsController::confirmAction()
     *
     * @return $this
     */
    protected function addQuoteRequestEditItemsConfirmRoute()
    {
        $this->createController('/{agent}/quote-request/edit-items-confirm/{quoteRequestReference}', static::ROUTE_QUOTE_REQUEST_AGENT_EDIT_ITEMS_CONFIRM, 'QuoteRequestAgentPage', 'QuoteRequestAgentEditItems', 'confirm')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent')
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
        $this->createController('/{agent}/quote-request/convert-to-cart/{quoteRequestReference}', static::ROUTE_QUOTE_REQUEST_AGENT_CONVERT_TO_CART, 'QuoteRequestAgentPage', 'QuoteRequestAgentCheckout', 'convertToCart')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent')
            ->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }
}
