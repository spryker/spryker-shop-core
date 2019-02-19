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
    public const ROUTE_AGENT_QUOTE_REQUEST = 'agent/quote-request';
    public const ROUTE_AGENT_QUOTE_REQUEST_CANCEL = 'agent/quote-request/cancel';

    protected const QUOTE_REQUEST_REFERENCE_REGEX = '[a-zA-Z0-9-]+';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addAgentQuoteRequestRoute()
            ->addAgentQuoteRequestCancelRoute();
    }

    /**
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
     * @return $this
     */
    protected function addAgentQuoteRequestCancelRoute()
    {
        $this->createController('/{agent}/quote-request/cancel/{quoteRequestReference}', static::ROUTE_AGENT_QUOTE_REQUEST_CANCEL, 'AgentQuoteRequestPage', 'AgentQuoteRequestDelete', 'cancel')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent')
            ->assert('quoteRequestReference', static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }
}
