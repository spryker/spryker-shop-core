<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Dependency\Client;

use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupsTransfer;

class CheckoutPageToShipmentClientBridge implements CheckoutPageToShipmentClientInterface
{
    /**
     * @var \Spryker\Client\Shipment\ShipmentClientInterface
     */
    protected $shipmentClient;

    /**
     * @param \Spryker\Client\Shipment\ShipmentClientInterface $shipmentClient
     */
    public function __construct($shipmentClient)
    {
        $this->shipmentClient = $shipmentClient;
    }

    /**
     * @deprecated Use getAvailableMethodsByShipment() instead
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer)
    {
        return $this->shipmentClient->getAvailableMethods($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupsTransfer
     */
    public function getAvailableMethodsByShipment(QuoteTransfer $quoteTransfer): ShipmentGroupsTransfer
    {
        return $this->shipmentClient->getAvailableMethodsByShipment($quoteTransfer);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemCollectionTransfer $itemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupsTransfer
     */
    public function getShipmentGroups(ItemCollectionTransfer $itemCollectionTransfer)
    {
        return $this->shipmentClient->getShipmentGroups($itemCollectionTransfer);
    }
}
