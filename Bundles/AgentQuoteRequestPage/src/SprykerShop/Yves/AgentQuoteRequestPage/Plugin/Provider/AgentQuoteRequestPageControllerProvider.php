<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class AgentQuoteRequestPageControllerProvider extends AbstractYvesControllerProvider
{
    protected const ROUTE_AGENT_QUOTE_REQUEST = 'agent/quote-request';
    protected const ROUTE_AGENT_QUOTE_REQUEST_CANCEL = 'agent/quote-request/cancel';
    protected const ROUTE_AGENT_QUOTE_REQUEST_DETAILS = 'agent/quote-request/details';
    protected const ROUTE_AGENT_QUOTE_REQUEST_EDIT = 'agent/quote-request/edit';
    protected const ROUTE_AGENT_QUOTE_REQUEST_CREATE = 'agent/quote-request/create';
    protected const ROUTE_AGENT_QUOTE_REQUEST_SEND_TO_CUSTOMER = 'agent/quote-request/send-to-customer';
    protected const ROUTE_AGENT_QUOTE_REQUEST_EDIT_ITEMS = 'agent/quote-request/edit-items';
    protected const ROUTE_AGENT_QUOTE_REQUEST_EDIT_ITEMS_CONFIRM = 'agent/quote-request/edit-items-confirm';
    protected const ROUTE_AGENT_QUOTE_REQUEST_REVISE = 'agent/quote-request/revise';

    protected const PARAM_QUOTE_REQUEST_REFERENCE = 'quoteRequestReference';

    protected const QUOTE_REQUEST_REFERENCE_REGEX = '[a-zA-Z0-9-]+';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addAgentQuoteRequestRoute()
            ->addAgentQuoteRequestCancelRoute()
            ->addQuoteRequestDetailsRoute()
            ->addQuoteRequestReviseRoute()
            ->addQuoteRequestEditRoute()
            ->addQuoteRequestSendToCustomerRoute()
            ->addQuoteRequestEditItemsRoute()
            ->addQuoteRequestEditItemsConfirmRoute()
            ->addQuoteRequestCreateRoute()
            ->addQuoteRequestSendToCustomerRoute();
    }

    /**
     * @uses \SprykerShop\Yves\AgentQuoteRequestPage\Controller\AgentQuoteRequestViewController::indexAction()
     *
     * @return $this
     */
    protected function addAgentQuoteRequestRoute()
    {
        $this->createController('/{agent}/quote-request', static::ROUTE_AGENT_QUOTE_REQUEST, 'AgentQuoteRequestPage', 'AgentQuoteRequestView')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent');

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\AgentQuoteRequestPage\Controller\AgentQuoteRequestDeleteController::cancelAction()
     *
     * @return $this
     */
    protected function addAgentQuoteRequestCancelRoute()
    {
        $this->createController('/{agent}/quote-request/cancel/{quoteRequestReference}', static::ROUTE_AGENT_QUOTE_REQUEST_CANCEL, 'AgentQuoteRequestPage', 'AgentQuoteRequestDelete', 'cancel')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent')
            ->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\AgentQuoteRequestPage\Controller\AgentQuoteRequestViewController::detailsAction()
     *
     * @return $this
     */
    protected function addQuoteRequestDetailsRoute()
    {
        $this->createController('/{agent}/quote-request/details/{quoteRequestReference}', static::ROUTE_AGENT_QUOTE_REQUEST_DETAILS, 'AgentQuoteRequestPage', 'AgentQuoteRequestView', 'details')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent')
            ->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\AgentQuoteRequestPage\Controller\AgentQuoteRequestEditController::startEditAction()
     *
     * @return $this
     */
    protected function addQuoteRequestReviseRoute()
    {
        $this->createController('/{agent}/quote-request/revise/{quoteRequestReference}', static::ROUTE_AGENT_QUOTE_REQUEST_REVISE, 'AgentQuoteRequestPage', 'AgentQuoteRequestRevise', 'index')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent')
            ->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\AgentQuoteRequestPage\Controller\AgentQuoteRequestEditController::editAction()
     *
     * @return $this
     */
    protected function addQuoteRequestEditRoute()
    {
        $this->createController('/{agent}/quote-request/edit/{quoteRequestReference}', static::ROUTE_AGENT_QUOTE_REQUEST_EDIT, 'AgentQuoteRequestPage', 'AgentQuoteRequestEdit', 'edit')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent')
            ->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\AgentQuoteRequestPage\Controller\AgentQuoteRequestEditController::editAction()
     *
     * @return $this
     */
    protected function addQuoteRequestCreateRoute()
    {
        $this->createController('/{agent}/quote-request/create', static::ROUTE_AGENT_QUOTE_REQUEST_CREATE, 'AgentQuoteRequestPage', 'AgentQuoteRequestCreate', 'create')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent');

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\AgentQuoteRequestPage\Controller\AgentQuoteRequestEditController::editAction()
     *
     * @return $this
     */
    protected function addQuoteRequestSendToCustomerRoute()
    {
        $this->createController('/{agent}/quote-request/send-to-customer/{quoteRequestReference}', static::ROUTE_AGENT_QUOTE_REQUEST_SEND_TO_CUSTOMER, 'AgentQuoteRequestPage', 'AgentQuoteRequestEdit', 'sendToCustomer')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent')
            ->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\AgentQuoteRequestPage\Controller\AgentQuoteRequestEditItemsController::indexAction()
     *
     * @return $this
     */
    protected function addQuoteRequestEditItemsRoute()
    {
        $this->createController('/{agent}/quote-request/edit-items/{quoteRequestReference}', static::ROUTE_AGENT_QUOTE_REQUEST_EDIT_ITEMS, 'AgentQuoteRequestPage', 'AgentQuoteRequestEditItems', 'index')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent')
            ->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\AgentQuoteRequestPage\Controller\AgentQuoteRequestEditItemsController::indexAction()
     *
     * @return $this
     */
    protected function addQuoteRequestEditItemsConfirmRoute()
    {
        $this->createController('/{agent}/quote-request/edit-items-confirm/{quoteRequestReference}', static::ROUTE_AGENT_QUOTE_REQUEST_EDIT_ITEMS_CONFIRM, 'AgentQuoteRequestPage', 'AgentQuoteRequestEditItems', 'confirm')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent')
            ->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }
}
