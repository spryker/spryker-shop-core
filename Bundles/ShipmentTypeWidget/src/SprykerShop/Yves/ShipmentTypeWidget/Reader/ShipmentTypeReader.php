<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShipmentTypeWidget\Reader;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use SprykerShop\Yves\ShipmentTypeWidget\Dependency\Client\ShipmentTypeWidgetToShipmentTypeStorageClientInterface;

class ShipmentTypeReader implements ShipmentTypeReaderInterface
{
    /**
     * @var \SprykerShop\Yves\ShipmentTypeWidget\Dependency\Client\ShipmentTypeWidgetToShipmentTypeStorageClientInterface
     */
    protected ShipmentTypeWidgetToShipmentTypeStorageClientInterface $shipmentTypeStorageClient;

    /**
     * @param \SprykerShop\Yves\ShipmentTypeWidget\Dependency\Client\ShipmentTypeWidgetToShipmentTypeStorageClientInterface $shipmentTypeStorageClient
     */
    public function __construct(ShipmentTypeWidgetToShipmentTypeStorageClientInterface $shipmentTypeStorageClient)
    {
        $this->shipmentTypeStorageClient = $shipmentTypeStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\ShipmentTypeTransfer>
     */
    public function getShipmentTypes(QuoteTransfer $quoteTransfer): array
    {
        $shipmentTypeStorageConditionsTransfer = (new ShipmentTypeStorageConditionsTransfer())
            ->setStoreName($quoteTransfer->getStoreOrFail()->getName());

        $shipmentTypeStorageCriteriaTransfer = (new ShipmentTypeStorageCriteriaTransfer())
            ->setShipmentTypeStorageConditions($shipmentTypeStorageConditionsTransfer);

        $shipmentTypeStorageCollectionTransfer = $this->shipmentTypeStorageClient
            ->getShipmentTypeStorageCollection($shipmentTypeStorageCriteriaTransfer);

        $shipmentTypeCollectionTransfer = $this->mapShipmentTypeStorageCollectionTransferToShipmentTypeCollectionTransfer(
            $shipmentTypeStorageCollectionTransfer,
            new ShipmentTypeCollectionTransfer(),
        );

        return $this->getShipmentTypesIndexedByKey($shipmentTypeCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\ShipmentTypeTransfer>
     */
    public function getAvailableShipmentTypes(QuoteTransfer $quoteTransfer): array
    {
        $shipmentTypeCollectionTransfer = $this->shipmentTypeStorageClient->getAvailableShipmentTypes($quoteTransfer);

        return $this->getShipmentTypesIndexedByKey($shipmentTypeCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer $shipmentTypeCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\ShipmentTypeTransfer>
     */
    protected function getShipmentTypesIndexedByKey(ShipmentTypeCollectionTransfer $shipmentTypeCollectionTransfer): array
    {
        $indexedShipmentTypes = [];

        foreach ($shipmentTypeCollectionTransfer->getShipmentTypes() as $shipmentTypeTransfer) {
            $indexedShipmentTypes[$shipmentTypeTransfer->getKeyOrFail()] = $shipmentTypeTransfer;
        }

        return $indexedShipmentTypes;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer
     * @param \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer $shipmentTypeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer
     */
    protected function mapShipmentTypeStorageCollectionTransferToShipmentTypeCollectionTransfer(
        ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer,
        ShipmentTypeCollectionTransfer $shipmentTypeCollectionTransfer
    ): ShipmentTypeCollectionTransfer {
        foreach ($shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages() as $shipmentTypeStorageTransfer) {
            $shipmentTypeCollectionTransfer->addShipmentType(
                (new ShipmentTypeTransfer())->fromArray($shipmentTypeStorageTransfer->toArray(), true),
            );
        }

        return $shipmentTypeCollectionTransfer;
    }
}
