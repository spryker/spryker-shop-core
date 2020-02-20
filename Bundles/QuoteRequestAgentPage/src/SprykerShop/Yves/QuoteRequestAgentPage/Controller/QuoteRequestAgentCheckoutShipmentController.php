<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Controller;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\QuoteRequestAgentPage\QuoteRequestAgentPageFactory getFactory()
 */
class QuoteRequestAgentCheckoutShipmentController extends QuoteRequestAgentAbstractController
{
    /**
     * @uses \SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin::CHECKOUT_SHIPMENT
     */
    protected const ROUTE_CHECKOUT_SHIPMENT = 'checkout-shipment';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::ROUTE_QUOTE_REQUEST_AGENT_CHECKOUT_SHIPMENT_CONFIRM
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_CHECKOUT_SHIPMENT_CONFIRM = 'agent/quote-request/checkout-shipment-confirm';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::ROUTE_QUOTE_REQUEST_AGENT_CHECKOUT_SHIPMENT
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_CHECKOUT_SHIPMENT = 'agent/quote-request/checkout-shipment';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $quoteRequestReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request, string $quoteRequestReference): RedirectResponse
    {
        $response = $this->executeIndexAction($request, $quoteRequestReference);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $quoteRequestReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function executeIndexAction(Request $request, string $quoteRequestReference): RedirectResponse
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();

        if ($quoteTransfer->getQuoteRequestReference() && ($quoteTransfer->getQuoteRequestReference() !== $quoteRequestReference)) {
            return $this->redirectResponseInternal(static::ROUTE_QUOTE_REQUEST_AGENT_CHECKOUT_SHIPMENT_CONFIRM, [
                static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestReference,
            ]);
        }

        $quoteRequestTransfer = $this->getQuoteRequestByReference($quoteRequestReference);

        return $this->prepareImpersonationRedirect($quoteRequestTransfer, $quoteTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $quoteRequestReference
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmAction(Request $request, string $quoteRequestReference)
    {
        $response = $this->executeConfirmAction($request, $quoteRequestReference);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@QuoteRequestAgentPage/views/quote-request-checkout-shipment-confirm/quote-request-checkout-shipment-confirm.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $quoteRequestReference
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeConfirmAction(Request $request, string $quoteRequestReference)
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();

        if ($quoteTransfer->getQuoteRequestReference() === $quoteRequestReference) {
            return $this->redirectResponseInternal(static::ROUTE_QUOTE_REQUEST_AGENT_CHECKOUT_SHIPMENT, [
                static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestReference,
            ]);
        }

        $quoteRequestTransfer = $this->getQuoteRequestByReference($quoteRequestReference);
        $quoteRequestAgentEditShipmentConfirmForm = $this->getFactory()
            ->getQuoteRequestAgentEditShipmentConfirmForm($quoteRequestTransfer)
            ->handleRequest($request);

        if ($quoteRequestAgentEditShipmentConfirmForm->isSubmitted()) {
            return $this->prepareImpersonationRedirect($quoteRequestAgentEditShipmentConfirmForm->getData(), $quoteTransfer);
        }

        return [
            'quoteRequestEditShipmentConfirmForm' => $quoteRequestAgentEditShipmentConfirmForm->createView(),
            'quoteRequestReference' => $quoteTransfer->getQuoteRequestReference(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function prepareImpersonationRedirect(QuoteRequestTransfer $quoteRequestTransfer, QuoteTransfer $quoteTransfer)
    {
        $companyUserTransfer = $this->getFactory()->getCompanyUserClient()->findCompanyUser();
        $impersonationRedirectParams = $this->getFactory()
            ->createCompanyUserImpersonator()
            ->getImpersonationCompanyUserExitParams($quoteRequestTransfer, $companyUserTransfer);

        if ($impersonationRedirectParams) {
            return $this->redirectResponseInternal(static::ROUTE_QUOTE_REQUEST_AGENT_CHECKOUT_SHIPMENT, $impersonationRedirectParams);
        }

        $this->getFactory()->createQuoteRequestConverter()->convertQuoteRequestToQuote($quoteRequestTransfer, $quoteTransfer);

        $impersonationRedirectParams = $this->getFactory()
            ->createCompanyUserImpersonator()
            ->getImpersonationCompanyUserEmailParams($quoteRequestTransfer, $companyUserTransfer);

        return $this->redirectResponseInternal(static::ROUTE_CHECKOUT_SHIPMENT, $impersonationRedirectParams);
    }
}
