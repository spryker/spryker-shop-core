<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestWidget\Plugin\CheckoutPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutShipmentPostExecutionRedirectStrategyPluginInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method \SprykerShop\Yves\QuoteRequestWidget\QuoteRequestWidgetFactory getFactory()
 */
class QuoteRequestWidgetCheckoutShipmentPostExecuteRedirectStrategyPlugin extends AbstractPlugin implements CheckoutShipmentPostExecutionRedirectStrategyPluginInterface
{
    /**
     * @uses @uses \SprykerShop\Yves\QuoteRequestPage\Plugin\Provider\QuoteRequestPageControllerProvider::ROUTE_QUOTE_REQUEST_EDIT
     */
    protected const ROUTE_QUOTE_REQUEST_EDIT = 'quote-request/edit';

    /**
     * {@inheritDoc}
     * - Checks if this plugin is applicable for provided quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isApplicable(QuoteTransfer $quoteTransfer): bool
    {
        return (bool)$quoteTransfer->getQuoteRequestReference();
    }

    /**
     * {@inheritDoc}
     * - Returns a redirect response to quote-request/cart/save.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\RedirectResponse $redirectResponse
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function execute(RedirectResponse $redirectResponse, QuoteTransfer $quoteTransfer): RedirectResponse
    {
        $checkoutShipmentUrl = $this->getFactory()
            ->getRouterService()
            ->generate(static::ROUTE_QUOTE_REQUEST_EDIT, ['quoteRequestReference' => $quoteTransfer->getQuoteRequestReference()]);

        return $this->getFactory()->createRedirectResponse($checkoutShipmentUrl);
    }
}
