<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Model\ShipmentGroups;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

class ShipmentGroupsBuilder implements ShipmentGroupsBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer[]|ArrayObject
     */
    public function buildShipmentGroups(OrderTransfer $orderTransfer): ArrayObject
    {
        if (!$orderTransfer->getIsMultiShipment()) {
            return $this->createShipmentGroups($orderTransfer);
        }

        return $orderTransfer->getShipmentGroups();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer[]|ArrayObject
     */
    protected function createShipmentGroups(OrderTransfer $orderTransfer): ArrayObject
    {
        $shipmentMethodTransfer = (new ShipmentMethodTransfer())
            ->setIdShipmentMethod($orderTransfer->getIdShipmentMethod());

        $shipmentTransfer = (new ShipmentTransfer())
            ->setShippingAddress($orderTransfer->getShippingAddress())
            ->setMethod($shipmentMethodTransfer);

        $shipmentGroupTransfer =  (new ShipmentGroupTransfer())
            ->setShipment($shipmentTransfer)
            ->setItems($orderTransfer->getItems());

        $shipmentGroups = new ArrayObject();
        $shipmentGroups->append($shipmentGroupTransfer);

        return $shipmentGroups;
    }
}
