<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Dependency\Service;

use ArrayObject;
use Generated\Shared\Transfer\ShipmentTransfer;

class CheckoutPageToShipmentServiceBridge implements CheckoutPageToShipmentServiceInterface
{
    /**
     * @var \Spryker\Service\Shipment\ShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @param \Spryker\Service\Shipment\ShipmentServiceInterface $shipmentService
     */
    public function __construct($shipmentService)
    {
        $this->shipmentService = $shipmentService;
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    public function groupItemsByShipment(iterable $itemTransfers): ArrayObject
    {
        return $this->shipmentService->groupItemsByShipment($itemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return string
     */
    public function getShipmentHashKey(ShipmentTransfer $shipmentTransfer): string
    {
        return $this->shipmentService->getShipmentHashKey($shipmentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param string $shipmentHashKey
     *
     * @return string
     */
    public function isShipmentEqualToShipmentHash(ShipmentTransfer $shipmentTransfer, string $shipmentHashKey): bool
    {
        return $this->shipmentService->isShipmentEqualToShipmentHash($shipmentTransfer, $shipmentHashKey);
    }
}
