<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class AgentQuoteRequestWidgetControllerProvider extends AbstractYvesControllerProvider
{
    protected const ROUTE_AGENT_QUOTE_REQUEST_SAVE_CART = 'agent/quote-request/cart/save';
    protected const ROUTE_AGENT_QUOTE_REQUEST_CLEAR_CART = 'agent/quote-request/cart/clear';
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
     * @uses \SprykerShop\Yves\AgentQuoteRequestWidget\Controller\AgentQuoteRequestCartController::saveAction()
     *
     * @return $this
     */
    protected function addQuoteRequestSaveCartRoute()
    {
        $this->createController('/{agent}/quote-request/cart/save', static::ROUTE_AGENT_QUOTE_REQUEST_SAVE_CART, 'AgentQuoteRequestWidget', 'AgentQuoteRequestCart', 'save')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent');

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\AgentQuoteRequestWidget\Controller\AgentQuoteRequestCartController::clearAction()
     *
     * @return $this
     */
    protected function addQuoteRequestClearCartRoute()
    {
        $this->createController('/{agent}/quote-request/cart/clear', static::ROUTE_AGENT_QUOTE_REQUEST_CLEAR_CART, 'AgentQuoteRequestWidget', 'AgentQuoteRequestCart', 'clear')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCompanyUserAutocompleteRoute()
    {
        $this->createController('/{agent}/company-user-autocomplete', static::ROUTE_AGENT_COMPANY_USER_AUTOCOMPLETE, 'AgentQuoteRequestWidget', 'AgentCompanyUserAutocomplete', 'index')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent');

        return $this;
    }
}
