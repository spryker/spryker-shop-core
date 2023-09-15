<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Builder;

class ServicePointAvailabilityMessageBuilder implements ServicePointAvailabilityMessageBuilderInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ALL_ITEMS_AVAILABLE = 'product_offer_service_point_availability_widget.all_items_available';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_SOME_ITEMS_NOT_AVAILABLE = 'product_offer_service_point_availability_widget.some_items_not_available';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ALL_ITEMS_NOT_AVAILABLE = 'product_offer_service_point_availability_widget.all_items_not_available';

    /**
     * @param array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>> $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid
     * @param bool $hasItemsWithoutProductOfferReferences
     *
     * @return array<string, string>
     */
    public function buildAvailabilityMessagesPerServicePoint(
        array $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid,
        bool $hasItemsWithoutProductOfferReferences
    ): array {
        $availabilityPerServicePoint = [];

        foreach ($productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid as $servicePointUuid => $productOfferServicePointAvailabilityResponseItemTransfers) {
            $hasAvailable = false;
            $hasNotAvailable = $hasItemsWithoutProductOfferReferences;

            foreach ($productOfferServicePointAvailabilityResponseItemTransfers as $productOfferServicePointAvailabilityResponseItemTransfer) {
                $hasAvailable = $hasAvailable || $productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailableOrFail();
                $hasNotAvailable = $hasNotAvailable || !$productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailableOrFail();
            }

            $availabilityPerServicePoint[$servicePointUuid] = $this->getAvailabilityMessageForSingleServicePoint(
                $hasAvailable,
                $hasNotAvailable,
            );
        }

        return $availabilityPerServicePoint;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ServicePointSearchTransfer> $servicePointSearchTransfers
     *
     * @return array<string, string>
     */
    public function buildUnavailableAvailabilityMessagesPerServicePoint(array $servicePointSearchTransfers): array
    {
        $availabilityPerServicePoint = [];

        foreach ($servicePointSearchTransfers as $servicePointSearchTransfer) {
            $availabilityPerServicePoint[$servicePointSearchTransfer->getUuidOrFail()] = static::GLOSSARY_KEY_ALL_ITEMS_NOT_AVAILABLE;
        }

        return $availabilityPerServicePoint;
    }

    /**
     * @param bool $hasAvailable
     * @param bool $hasNotAvailable
     *
     * @return string
     */
    protected function getAvailabilityMessageForSingleServicePoint(
        bool $hasAvailable,
        bool $hasNotAvailable
    ): string {
        if ($hasAvailable) {
            if ($hasNotAvailable) {
                return static::GLOSSARY_KEY_SOME_ITEMS_NOT_AVAILABLE;
            }

            return static::GLOSSARY_KEY_ALL_ITEMS_AVAILABLE;
        }

        return static::GLOSSARY_KEY_ALL_ITEMS_NOT_AVAILABLE;
    }
}
