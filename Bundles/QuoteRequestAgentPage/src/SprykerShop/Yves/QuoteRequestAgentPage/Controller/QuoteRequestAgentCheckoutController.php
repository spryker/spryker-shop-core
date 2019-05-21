<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method \SprykerShop\Yves\QuoteRequestAgentPage\QuoteRequestAgentPageFactory getFactory()
 */
class QuoteRequestAgentCheckoutController extends QuoteRequestAgentAbstractController
{
    /**
     * @param string $quoteRequestReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function convertToCartAction(string $quoteRequestReference): RedirectResponse
    {
        $response = $this->executeConvertToCartActionAction($quoteRequestReference);

        return $response;
    }

    /**
     * @param string $quoteRequestReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeConvertToCartActionAction(string $quoteRequestReference): RedirectResponse
    {
        $quoteRequestTransfer = $this->getQuoteRequestByReference($quoteRequestReference);
        $companyUserTransfer = $this->getFactory()
            ->getCompanyUserClient()
            ->findCompanyUser();

        if (!$companyUserTransfer) {
            return $this->redirectResponseInternal(static::ROUTE_QUOTE_REQUEST_CONVERT_TO_CART, [
                static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestTransfer->getQuoteRequestReference(),
                static::PARAM_SWITCH_USER => $quoteRequestTransfer->getCompanyUser()->getCustomer()->getEmail(),
            ]);
        }

        if ($companyUserTransfer->getIdCompanyUser() !== $quoteRequestTransfer->getCompanyUser()->getIdCompanyUser()) {
            return $this->redirectResponseInternal(static::ROUTE_QUOTE_REQUEST_AGENT_CONVERT_TO_CART, [
                static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestTransfer->getQuoteRequestReference(),
                static::PARAM_SWITCH_USER => '_exit',
            ]);
        }

        return $this->redirectResponseInternal(static::ROUTE_QUOTE_REQUEST_CONVERT_TO_CART, [
            static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestTransfer->getQuoteRequestReference(),
        ]);
    }
}
