<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage\Controller;

use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\AgentQuoteRequestPage\AgentQuoteRequestPageFactory getFactory()
 */
class AgentQuoteRequestAbstractController extends AbstractController
{
    /**
     * @uses \SprykerShop\Yves\AgentQuoteRequestPage\Plugin\Provider\AgentQuoteRequestPageControllerProvider::PARAM_QUOTE_REQUEST_REFERENCE
     */
    protected const PARAM_QUOTE_REQUEST_REFERENCE = 'quoteRequestReference';
    protected const PARAM_QUOTE_REQUEST_VERSION_REFERENCE = '%quoteRequestVersionReference%';

    /**
     * @uses \SprykerShop\Yves\AgentQuoteRequestPage\Plugin\Provider\AgentQuoteRequestPageControllerProvider::ROUTE_AGENT_QUOTE_REQUEST_EDIT_ITEMS
     */
    protected const ROUTE_AGENT_QUOTE_REQUEST_EDIT_ITEMS = 'agent/quote-request/edit-items';

    /**
     * @uses \SprykerShop\Yves\AgentQuoteRequestPage\Plugin\Provider\AgentQuoteRequestPageControllerProvider::ROUTE_AGENT_QUOTE_REQUEST_EDIT_ITEMS_CONFIRM
     */
    public const ROUTE_AGENT_QUOTE_REQUEST_EDIT_ITEMS_CONFIRM = 'agent/quote-request/edit-items-confirm';

    /**
     * @see \SprykerShop\Yves\AgentQuoteRequestPage\Plugin\Provider\AgentQuoteRequestPageControllerProvider::ROUTE_AGENT_QUOTE_REQUEST_DETAILS
     */
    protected const ROUTE_AGENT_QUOTE_REQUEST_DETAILS = 'agent/quote-request/details';

    /**
     * @see \SprykerShop\Yves\AgentQuoteRequestPage\Plugin\Provider\AgentQuoteRequestPageControllerProvider::ROUTE_AGENT_QUOTE_REQUEST_EDIT
     */
    protected const ROUTE_AGENT_QUOTE_REQUEST_EDIT = 'agent/quote-request/edit';

    /**
     * @see \SprykerShop\Yves\AgentQuoteRequestPage\Plugin\Provider\AgentQuoteRequestPageControllerProvider::ROUTE_AGENT_QUOTE_REQUEST_REVISE
     */
    protected const ROUTE_AGENT_QUOTE_REQUEST_REVISE = 'agent/quote-request/revise';

    /**
     * @see \SprykerShop\Yves\AgentQuoteRequestPage\Plugin\Provider\AgentQuoteRequestPageControllerProvider::ROUTE_AGENT_QUOTE_REQUEST_SEND_TO_CUSTOMER
     */
    protected const ROUTE_AGENT_QUOTE_REQUEST_SEND_TO_CUSTOMER = 'agent/quote-request/send-to-customer';

    /**
     * @see \SprykerShop\Yves\AgentQuoteRequestPage\Plugin\Provider\AgentQuoteRequestPageControllerProvider::ROUTE_AGENT_QUOTE_REQUEST
     */
    protected const ROUTE_AGENT_QUOTE_REQUEST = 'agent/quote-request';

    /**
     * @param string $quoteRequestReference
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function getQuoteRequestByReference(string $quoteRequestReference): QuoteRequestTransfer
    {
        $quoteRequestTransfer = $this->getFactory()
            ->getAgentQuoteRequestClient()
            ->findQuoteRequestByReference($quoteRequestReference);

        if (!$quoteRequestTransfer) {
            throw new NotFoundHttpException();
        }

        return $quoteRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer
     *
     * @return void
     */
    protected function handleResponseErrors(QuoteRequestResponseTransfer $quoteRequestResponseTransfer): void
    {
        foreach ($quoteRequestResponseTransfer->getMessages() as $messageTransfer) {
            $this->addErrorMessage($messageTransfer->getValue());
        }
    }

    /**
     * @param string $key
     * @param array $params
     *
     * @return string
     */
    protected function getTranslatedMessage(string $key, array $params = []): string
    {
        return $this->getFactory()
            ->getGlossaryStorageClient()
            ->translate($key, $this->getLocale(), $params);
    }

    /**
     * @param string $key
     * @param array $params
     *
     * @return void
     */
    protected function addTranslatedSuccessMessage(string $key, array $params = []): void
    {
        $this->addSuccessMessage(
            $this->getTranslatedMessage($key, $params)
        );
    }
}
