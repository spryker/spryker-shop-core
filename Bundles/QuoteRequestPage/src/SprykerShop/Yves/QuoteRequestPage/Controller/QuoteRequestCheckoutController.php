<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Controller;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Spryker\Client\Kernel\Locator;
use SprykerShop\Yves\QuoteRequestPage\Plugin\Provider\QuoteRequestPageControllerProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 * @method \Spryker\Client\QuoteRequest\QuoteRequestClient getClient()
 */
class QuoteRequestCheckoutController extends QuoteRequestAbstractController
{
    /**
     * @uses \SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider::ROUTE_CART
     */
    protected const ROUTE_CART = 'cart';

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function orderAction(string $quoteRequestReference): RedirectResponse
    {
        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteRequestReference)
            ->setCompanyUser($this->getFactory()->getCompanyUserClient()->findCompanyUser());

        $quoteRequestTransfers = $this->getFactory()
            ->getQuoteRequestClient()
            ->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer)
            ->getQuoteRequests()
            ->getArrayCopy();

        $quoteRequestTransfer = array_shift($quoteRequestTransfers);

         $this->getFactory()
            ->getQuoteRequestClient()
            ->convertToQuote($quoteRequestTransfer);

        return $this->redirectResponseInternal(static::ROUTE_CART);
    }
}
