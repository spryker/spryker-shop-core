<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\CheckoutShipmentOperation;

use Generated\Shared\Transfer\QuoteTransfer;
use Symfony\Cmf\Component\Routing\ChainRouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

interface CheckoutShipmentPostExecuterInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\RedirectResponse $response
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Cmf\Component\Routing\ChainRouterInterface $router
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function executeCheckoutShipmentPostExecutePlugins(RedirectResponse $response, QuoteTransfer $quoteTransfer, ChainRouterInterface $router): RedirectResponse;
}
