<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Form\Handler;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client\AgentQuoteRequestPageToAgentQuoteRequestClientInterface;

class AgentQuoteRequestCreateHandler implements AgentQuoteRequestCreateHandlerInterface
{
    /**
     * @var \SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client\AgentQuoteRequestPageToAgentQuoteRequestClientInterface
     */
    protected $agentQuoteRequestClient;

    /**
     * @param \SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client\AgentQuoteRequestPageToAgentQuoteRequestClientInterface $agentQuoteRequestClient
     */
    public function __construct(AgentQuoteRequestPageToAgentQuoteRequestClientInterface $agentQuoteRequestClient)
    {
        $this->agentQuoteRequestClient = $agentQuoteRequestClient;
    }

    /**
     * @param int|null $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function createQuoteRequest(?int $idCompanyUser): QuoteRequestResponseTransfer
    {
        $quoteRequestTransfer = (new QuoteRequestTransfer())
            ->setCompanyUser((new CompanyUserTransfer())->setIdCompanyUser($idCompanyUser));

        // TODO:: create quote request
    }
}
