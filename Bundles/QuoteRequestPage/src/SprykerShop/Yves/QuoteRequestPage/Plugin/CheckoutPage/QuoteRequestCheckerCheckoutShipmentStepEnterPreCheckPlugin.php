<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Plugin\CheckoutPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutShipmentStepEnterPreCheckPluginInterface;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class QuoteRequestCheckerCheckoutShipmentStepEnterPreCheckPlugin extends AbstractPlugin implements CheckoutShipmentStepEnterPreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if the shipment step should be hidden, returns true if quote request version reference and custom shipment price are set, false otherwise.
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
            ->isQuoteRequestForQuoteWithCustomShipmentPrice($quoteTransfer);
    }
}
