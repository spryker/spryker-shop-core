<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Controller;

use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\QuoteRequestAgentPage\QuoteRequestAgentPageFactory getFactory()
 */
class QuoteRequestAgentAbstractController extends AbstractController
{
    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Provider\QuoteRequestAgentPageControllerProvider::PARAM_QUOTE_REQUEST_REFERENCE
     */
    protected const PARAM_QUOTE_REQUEST_REFERENCE = 'quoteRequestReference';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Provider\QuoteRequestAgentPageControllerProvider::ROUTE_QUOTE_REQUEST_AGENT_EDIT_ITEMS
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_EDIT_ITEMS = 'agent/quote-request/edit-items';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Provider\QuoteRequestAgentPageControllerProvider::ROUTE_QUOTE_REQUEST_AGENT_EDIT_ITEMS_CONFIRM
     */
    public const ROUTE_QUOTE_REQUEST_AGENT_EDIT_ITEMS_CONFIRM = 'agent/quote-request/edit-items-confirm';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Provider\QuoteRequestAgentPageControllerProvider::ROUTE_QUOTE_REQUEST_AGENT_DETAILS
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_DETAILS = 'agent/quote-request/details';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Provider\QuoteRequestAgentPageControllerProvider::ROUTE_QUOTE_REQUEST_AGENT_EDIT
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_EDIT = 'agent/quote-request/edit';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Provider\QuoteRequestAgentPageControllerProvider::ROUTE_QUOTE_REQUEST_AGENT_REVISE
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_REVISE = 'agent/quote-request/revise';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Provider\QuoteRequestAgentPageControllerProvider::ROUTE_QUOTE_REQUEST_AGENT_SEND_TO_CUSTOMER
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_SEND_TO_CUSTOMER = 'agent/quote-request/send-to-customer';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Provider\QuoteRequestAgentPageControllerProvider::ROUTE_QUOTE_REQUEST_AGENT
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT = 'agent/quote-request';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Provider\QuoteRequestAgentPageControllerProvider::ROUTE_QUOTE_REQUEST_AGENT_CONVERT_TO_CART
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_CONVERT_TO_CART = 'agent/quote-request/convert-to-cart';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Plugin\Provider\QuoteRequestPageControllerProvider::ROUTE_QUOTE_REQUEST_CONVERT_TO_CART
     */
    protected const ROUTE_QUOTE_REQUEST_CONVERT_TO_CART = 'quote-request/convert-to-cart';

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
            ->getQuoteRequestAgentClient()
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
}
