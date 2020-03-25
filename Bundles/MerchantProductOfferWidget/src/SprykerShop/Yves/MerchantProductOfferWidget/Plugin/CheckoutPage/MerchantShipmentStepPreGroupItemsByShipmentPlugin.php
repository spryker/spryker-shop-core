<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Plugin\CheckoutPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutShipmentStepPreGroupItemsByShipmentPluginInterface;

class MerchantShipmentStepPreGroupItemsByShipmentPlugin extends AbstractPlugin implements CheckoutShipmentStepPreGroupItemsByShipmentPluginInterface
{
    /**
     * {@inheritDoc}
     * - Sets merchant reference in shipment of item transfers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function preGroupItemsByShipment(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->getShipment()->setMerchantReference($itemTransfer->getMerchantReference());
        }

        return $quoteTransfer;
    }
}
