<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage\Form\DataProvider;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client\AgentQuoteRequestPageToQuoteRequestClientInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AgentQuoteRequestFormDataProvider
{
    /**
     * @var \SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client\AgentQuoteRequestPageToQuoteRequestClientInterface
     */
    protected $quoteRequestClient;

    /**
     * @param \SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client\AgentQuoteRequestPageToQuoteRequestClientInterface $quoteRequestClient
     */
    public function __construct(AgentQuoteRequestPageToQuoteRequestClientInterface $quoteRequestClient)
    {
        $this->quoteRequestClient = $quoteRequestClient;
    }

    /**
     * @param string|null $agentQuoteRequestReference
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function getData(string $agentQuoteRequestReference): QuoteRequestTransfer
    {
        return $this->getAgentQuoteRequestTransferByReference($agentQuoteRequestReference);
    }

    /**
     * @param string $agentQuoteRequestReference
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function getAgentQuoteRequestTransferByReference(string $agentQuoteRequestReference): QuoteRequestTransfer
    {
        $agentQuoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($agentQuoteRequestReference)
            ->setWithHidden(true);

        $agentQuoteRequests = $this->quoteRequestClient
            ->getQuoteRequestCollectionByFilter($agentQuoteRequestFilterTransfer)
            ->getQuoteRequests()
            ->getArrayCopy();

        $agentQuoteRequestTransfer = array_shift($agentQuoteRequests);

        if (!$agentQuoteRequestTransfer) {
            throw new NotFoundHttpException();
        }

        return $agentQuoteRequestTransfer;
    }
}
