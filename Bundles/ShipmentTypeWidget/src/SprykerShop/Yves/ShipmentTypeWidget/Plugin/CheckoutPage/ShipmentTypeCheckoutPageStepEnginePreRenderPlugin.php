<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShipmentTypeWidget\Plugin\CheckoutPage;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\StepEngine\CheckoutPageStepEnginePreRenderPluginInterface;

/**
 * @method \SprykerShop\Yves\ShipmentTypeWidget\ShipmentTypeWidgetFactory getFactory()
 */
class ShipmentTypeCheckoutPageStepEnginePreRenderPlugin extends AbstractPlugin implements CheckoutPageStepEnginePreRenderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `Quote.items.shipment` to be set.
     * - Does nothing if `Quote.items.shipmentType.uuid` is empty.
     * - Sets `Quote.items.shipment.shipmentTypeUuid` taken from `Quote.items.shipmentType.uuid`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(AbstractTransfer $quoteTransfer): AbstractTransfer
    {
        return $this->getFactory()->createQuoteExpander()->expandQuoteItemsWithShipmentType($quoteTransfer);
    }
}
