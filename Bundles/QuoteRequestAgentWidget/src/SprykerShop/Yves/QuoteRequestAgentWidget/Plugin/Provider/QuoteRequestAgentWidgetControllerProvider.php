<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class QuoteRequestAgentWidgetControllerProvider extends AbstractYvesControllerProvider
{
    protected const ROUTE_QUOTE_REQUEST_AGENT_SAVE_CART = 'agent/quote-request/cart/save';
    protected const ROUTE_QUOTE_REQUEST_AGENT_CLEAR_CART = 'agent/quote-request/cart/clear';
    protected const ROUTE_AGENT_COMPANY_USER_AUTOCOMPLETE = 'agent/company-user-autocomplete';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addQuoteRequestSaveCartRoute()
            ->addQuoteRequestClearCartRoute()
            ->addCompanyUserAutocompleteRoute();
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentWidget\Controller\QuoteRequestAgentCartController::saveAction()
     *
     * @return $this
     */
    protected function addQuoteRequestSaveCartRoute()
    {
        $this->createController('/{agent}/quote-request/cart/save', static::ROUTE_QUOTE_REQUEST_AGENT_SAVE_CART, 'QuoteRequestAgentWidget', 'QuoteRequestAgentCart', 'save')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent');

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentWidget\Controller\QuoteRequestAgentCartController::clearAction()
     *
     * @return $this
     */
    protected function addQuoteRequestClearCartRoute()
    {
        $this->createController('/{agent}/quote-request/cart/clear', static::ROUTE_QUOTE_REQUEST_AGENT_CLEAR_CART, 'QuoteRequestAgentWidget', 'QuoteRequestAgentCart', 'clear')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCompanyUserAutocompleteRoute()
    {
        $this->createController('/{agent}/company-user-autocomplete', static::ROUTE_AGENT_COMPANY_USER_AUTOCOMPLETE, 'QuoteRequestAgentWidget', 'AgentCompanyUserAutocomplete', 'index')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent');

        return $this;
    }
}
