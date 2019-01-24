<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Controller;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use SprykerShop\Yves\QuoteRequestPage\Plugin\Provider\QuoteRequestPageControllerProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class QuoteRequestDeleteController extends QuoteRequestAbstractController
{
    /**
     * @param string $quoteRequestReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function cancelAction(string $quoteRequestReference): RedirectResponse
    {
        $companyUserTransfer = $this->getFactory()
            ->getCompanyUserClient()
            ->findCompanyUser();

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteRequestReference)
            ->setCompanyUser($companyUserTransfer);

        $this->getFactory()->getQuoteRequestClient()->cancelByReference($quoteRequestFilterTransfer);

        return $this->redirectResponseInternal(QuoteRequestPageControllerProvider::ROUTE_QUOTE_REQUEST);
    }
}
