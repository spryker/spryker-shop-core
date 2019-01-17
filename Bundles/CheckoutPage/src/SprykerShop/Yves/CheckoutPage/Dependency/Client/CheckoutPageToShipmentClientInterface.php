<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupCollectionTransfer;

interface CheckoutPageToShipmentClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupCollectionTransfer
     */
    public function getAvailableMethodsByShipment(QuoteTransfer $quoteTransfer): ShipmentGroupCollectionTransfer;
}
