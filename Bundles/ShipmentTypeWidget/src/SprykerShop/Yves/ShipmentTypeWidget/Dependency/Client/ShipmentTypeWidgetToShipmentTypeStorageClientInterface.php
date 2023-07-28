<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShipmentTypeWidget\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCriteriaTransfer;

interface ShipmentTypeWidgetToShipmentTypeStorageClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageCriteriaTransfer $shipmentTypeStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer
     */
    public function getShipmentTypeStorageCollection(
        ShipmentTypeStorageCriteriaTransfer $shipmentTypeStorageCriteriaTransfer
    ): ShipmentTypeStorageCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer
     */
    public function getAvailableShipmentTypes(QuoteTransfer $quoteTransfer): ShipmentTypeCollectionTransfer;
}
