<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Expander;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;

interface ShipmentGroupExpanderInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    public function expandShipmentGroupsWithCartItems(ArrayObject $shipmentGroupTransfers, OrderTransfer $orderTransfer): ArrayObject;
}
