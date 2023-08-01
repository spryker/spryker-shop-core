<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShipmentTypeWidget\Plugin\CustomerPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\CheckoutAddressStepPreGroupItemsByShipmentPluginInterface;

/**
 * @method \SprykerShop\Yves\ShipmentTypeWidget\ShipmentTypeWidgetFactory getFactory()
 */
class ShipmentTypeCheckoutAddressStepPreGroupItemsByShipmentPlugin extends AbstractPlugin implements CheckoutAddressStepPreGroupItemsByShipmentPluginInterface
{
    /**
     * {@inheritDoc}
     * - Cleans `Shipment.shipmentTypeUuid` from each item in `Quote.items`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function preGroupItemsByShipment(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()->createQuoteCleaner()->cleanShipmentTypeUuidFromQuoteItems($quoteTransfer);
    }
}
