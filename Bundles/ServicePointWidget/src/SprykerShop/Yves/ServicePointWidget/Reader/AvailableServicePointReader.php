<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointWidget\Reader;

use Generated\Shared\Transfer\ServicePointStorageCollectionTransfer;
use Generated\Shared\Transfer\ServicePointStorageConditionsTransfer;
use Generated\Shared\Transfer\ServicePointStorageCriteriaTransfer;
use Generated\Shared\Transfer\ServicePointStorageTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use SprykerShop\Yves\ServicePointWidget\Dependency\Client\ServicePointWidgetToServicePointStorageClientInterface;

class AvailableServicePointReader implements AvailableServicePointReaderInterface
{
    /**
     * @var array<string, \Generated\Shared\Transfer\ServicePointTransfer>
     */
    protected static array $availableServicePoints = [];

    /**
     * @var \SprykerShop\Yves\ServicePointWidget\Dependency\Client\ServicePointWidgetToServicePointStorageClientInterface
     */
    protected ServicePointWidgetToServicePointStorageClientInterface $servicePointStorageClient;

    /**
     * @param \SprykerShop\Yves\ServicePointWidget\Dependency\Client\ServicePointWidgetToServicePointStorageClientInterface $servicePointStorageClient
     */
    public function __construct(
        ServicePointWidgetToServicePointStorageClientInterface $servicePointStorageClient
    ) {
        $this->servicePointStorageClient = $servicePointStorageClient;
    }

    /**
     * @param list<string> $servicePointUuids
     * @param string $storeName
     *
     * @return array<string, \Generated\Shared\Transfer\ServicePointTransfer>
     */
    public function getServicePoints(array $servicePointUuids, string $storeName): array
    {
        if (static::$availableServicePoints) {
            return static::$availableServicePoints;
        }

        $servicePointStorageConditionsTransfer = (new ServicePointStorageConditionsTransfer())
            ->setUuids($servicePointUuids)
            ->setStoreName($storeName);

        $servicePointStorageCriteriaTransfer = (new ServicePointStorageCriteriaTransfer())
            ->setServicePointStorageConditions($servicePointStorageConditionsTransfer);

        $servicePointCollectionTransfer = $this->servicePointStorageClient->getServicePointStorageCollection($servicePointStorageCriteriaTransfer);
        static::$availableServicePoints = $this->getServicePointsIndexedByUuid($servicePointCollectionTransfer);

        return static::$availableServicePoints;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointStorageCollectionTransfer $servicePointStorageCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\ServicePointTransfer>
     */
    protected function getServicePointsIndexedByUuid(ServicePointStorageCollectionTransfer $servicePointStorageCollectionTransfer): array
    {
        $indexedServicePoints = [];
        foreach ($servicePointStorageCollectionTransfer->getServicePointStorages() as $servicePointStorageTransfer) {
            $servicePointUuid = $servicePointStorageTransfer->getUuidOrFail();

            $indexedServicePoints[$servicePointUuid] = $this->mapServicePointStorageTransferToServicePointTransfer(
                $servicePointStorageTransfer,
                new ServicePointTransfer(),
            );
        }

        return $indexedServicePoints;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointStorageTransfer $servicePointStorageTransfer
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    protected function mapServicePointStorageTransferToServicePointTransfer(
        ServicePointStorageTransfer $servicePointStorageTransfer,
        ServicePointTransfer $servicePointTransfer
    ): ServicePointTransfer {
        $servicePointData = $servicePointStorageTransfer->toArray();
        unset($servicePointData[ServicePointStorageTransfer::SERVICES]);

        return $servicePointTransfer->fromArray($servicePointData, true);
    }
}
