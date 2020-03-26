<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Provides preparation of quote transfer at the shipment step before grouping.
 */
interface CheckoutShipmentStepPreGroupItemsByShipmentPluginInterface
{
    /**
     * Specifications:
     * - Prepares quote transfer before grouping.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function preGroupItemsByShipment(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
