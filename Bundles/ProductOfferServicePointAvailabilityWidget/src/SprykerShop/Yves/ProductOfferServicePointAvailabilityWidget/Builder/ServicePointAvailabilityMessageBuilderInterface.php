<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Builder;

interface ServicePointAvailabilityMessageBuilderInterface
{
    /**
     * @param array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>> $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid
     * @param bool $hasItemsWithoutProductOfferReferences
     *
     * @return array<string, string>
     */
    public function buildAvailabilityMessagesPerServicePoint(
        array $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid,
        bool $hasItemsWithoutProductOfferReferences
    ): array;

    /**
     * @param list<\Generated\Shared\Transfer\ServicePointSearchTransfer> $servicePointSearchTransfers
     *
     * @return array<string, string>
     */
    public function buildUnavailableAvailabilityMessagesPerServicePoint(array $servicePointSearchTransfers): array;
}
