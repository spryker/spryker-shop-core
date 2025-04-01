<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Reader;

interface ProductOfferServicePointAvailabilityReaderInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\ServicePointSearchTransfer> $servicePointSearchTransfers
     * @param list<string> $groupKeys
     * @param string|null $serviceTypeUuid
     * @param string|null $shipmentTypeUuid
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<int, array<string, string>|array<string, array<int, array<string, mixed>>>>
     */
    public function getProductOfferServicePointAvailabilities(
        array $servicePointSearchTransfers,
        array $groupKeys,
        ?string $serviceTypeUuid = null,
        ?string $shipmentTypeUuid = null,
        array $itemTransfers = []
    ): array;
}
