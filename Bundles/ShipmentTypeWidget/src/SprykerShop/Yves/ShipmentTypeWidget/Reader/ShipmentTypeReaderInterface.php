<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShipmentTypeWidget\Reader;

use Generated\Shared\Transfer\QuoteTransfer;

interface ShipmentTypeReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\ShipmentTypeTransfer>
     */
    public function getShipmentTypes(QuoteTransfer $quoteTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\ShipmentTypeTransfer>
     */
    public function getAvailableShipmentTypes(QuoteTransfer $quoteTransfer): array;
}
