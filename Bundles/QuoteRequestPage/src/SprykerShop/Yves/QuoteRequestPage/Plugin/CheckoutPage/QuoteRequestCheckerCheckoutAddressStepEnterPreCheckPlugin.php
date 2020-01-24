<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Plugin\CheckoutPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutAddressStepEnterPreCheckPluginInterface;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class QuoteRequestCheckerCheckoutAddressStepEnterPreCheckPlugin extends AbstractPlugin implements CheckoutAddressStepEnterPreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if the address step must be hidden.
     * - Returns true if quote request version reference and custom shipment price are set, false otherwise..
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function check(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFactory()
            ->getQuoteRequestClient()
            ->isQuoteInQuoteRequestProcess($quoteTransfer);
    }
}
