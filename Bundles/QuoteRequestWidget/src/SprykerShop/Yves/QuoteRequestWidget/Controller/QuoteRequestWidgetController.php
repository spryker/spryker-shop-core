<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestWidget\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\QuoteRequestWidget\QuoteRequestWidgetConfig;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\QuoteRequestWidget\QuoteRequestWidgetFactory getFactory()
 * @method \SprykerShop\Yves\QuoteRequestWidget\QuoteRequestWidgetConfig getConfig()
 */
class QuoteRequestWidgetController extends AbstractController
{
    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $customerTransfer = $this->getCustomer();

        if ($customerTransfer === null || !$customerTransfer->getCompanyUserTransfer()) {
            throw new NotFoundHttpException("Only company users are allowed to access this page");
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        $quoteTransfer = $this->getFactory()
            ->getQuoteClient()
            ->getQuote();

        $quoteRequestTransfer = $this->getFactory()
            ->getQuoteRequestClient()
            ->createQuoteRequestFromQuote($quoteTransfer);

        return $this->redirectResponseInternal(QuoteRequestWidgetConfig::QUOTE_REQUEST_REDIRECT_URL, [
            'quoteRequestReference' => $quoteRequestTransfer->getQuoteRequestReference(),
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    protected function getCustomer(): ?CustomerTransfer
    {
        return $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();
    }
}
