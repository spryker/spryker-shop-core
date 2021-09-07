<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Controller;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
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
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::PARAM_QUOTE_REQUEST_REFERENCE
     * @var string
     */
    protected const PARAM_QUOTE_REQUEST_REFERENCE = 'quoteRequestReference';
    /**
     * @var string
     */
    protected const PARAM_SWITCH_USER = '_switch_user';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_DETAILS} instead.
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_DETAILS = 'agent/quote-request/details';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_EDIT} instead.
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_EDIT = 'agent/quote-request/edit';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_REVISE} instead.
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_REVISE = 'agent/quote-request/revise';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_SEND_TO_CUSTOMER} instead.
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_SEND_TO_CUSTOMER = 'agent/quote-request/send-to-customer';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT} instead.
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT = 'agent/quote-request';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_CONVERT_TO_CART} instead.
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_CONVERT_TO_CART = 'agent/quote-request/convert-to-cart';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_CONVERT_TO_CART
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_CONVERT_TO_CART = 'quote-request/convert-to-cart';

    /**
     * @param string $quoteRequestReference
     * @param bool $withVersions
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function getQuoteRequestByReference(
        string $quoteRequestReference,
        bool $withVersions = false
    ): QuoteRequestTransfer {
        $quoteRequestTransfer = $this->getFactory()
            ->getQuoteRequestAgentClient()
            ->findQuoteRequest(
                (new QuoteRequestFilterTransfer())
                    ->setQuoteRequestReference($quoteRequestReference)
                    ->setWithVersions($withVersions)
            );

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
