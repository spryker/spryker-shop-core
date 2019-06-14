<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Model\ShipmentGroups;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;

interface ShipmentGroupsBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return ArrayObject
     */
    public function buildShipmentGroups(OrderTransfer $orderTransfer): ArrayObject;
}
