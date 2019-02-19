<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client\AgentQuoteRequestPageToAgentQuoteRequestClientInterface;
use SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client\AgentQuoteRequestPageToQuoteRequestClientInterface;

/**
 * @method \SprykerShop\Yves\AgentQuoteRequestPage\AgentQuoteRequestPageConfig getConfig()
 */
class AgentQuoteRequestPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client\AgentQuoteRequestPageToQuoteRequestClientInterface
     */
    public function getQuoteRequestClient(): AgentQuoteRequestPageToQuoteRequestClientInterface
    {
        return $this->getProvidedDependency(AgentQuoteRequestPageDependencyProvider::CLIENT_QUOTE_REQUEST);
    }

    /**
     * @return \SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client\AgentQuoteRequestPageToAgentQuoteRequestClientInterface
     */
    public function getAgentQuoteRequestClient(): AgentQuoteRequestPageToAgentQuoteRequestClientInterface
    {
        return $this->getProvidedDependency(AgentQuoteRequestPageDependencyProvider::CLIENT_AGENT_QUOTE_REQUEST);
    }
}
