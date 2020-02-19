<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\Controller;

use Generated\Shared\Transfer\MerchantSwitchRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcherWidgetFactory getFactory()
 * @method \SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcherWidgetConfig getConfig()
 */
class MerchantSwitcherController extends AbstractController
{
    protected const PARAM_MERCHANT_REFERENCE = 'merchant-reference';
    protected const HEADER_REFERER = 'referer';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function switchMerchantAction(Request $request): RedirectResponse
    {
        $merchantReference = $request->get(static::PARAM_MERCHANT_REFERENCE);

        $this->updateQuoteWithMerchantReference($merchantReference);

        $this->getFactory()->createSelectedMerchantCookie()->setMerchantReference($merchantReference);

        return $this->createRedirectResponse($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function createRedirectResponse(Request $request): RedirectResponse
    {
        return $this->redirectResponseExternal($request->headers->get(static::HEADER_REFERER));
    }

    /**
     * @param string $merchantReference
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function updateQuoteWithMerchantReference(string $merchantReference): QuoteTransfer
    {
        $quoteClient = $this->getFactory()->getQuoteClient();

        $quoteTransfer = $quoteClient->getQuote();

        $quoteTransfer = $this->getFactory()
            ->getMerchantSwitcherClient()
            ->switchMerchant(
                (new MerchantSwitchRequestTransfer())
                    ->setQuote($quoteTransfer)
                    ->setMerchantReference($merchantReference)
            )->getQuote();

        $quoteClient->setQuote($quoteTransfer);

        return $quoteTransfer;
    }
}
