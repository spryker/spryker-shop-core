<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Builder;

use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer;

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
     * @var string
     */
    protected const KEY_PRODUCT_OFFER_AVAILABILITY_BY_SERVICE_POINT = 'productOfferAvailabilityByServicePointUuid';

    /**
     * @var string
     */
    protected const KEY_PRODUCT_OFFER_REFERENCE = 'productOfferReference';

    /**
     * @var string
     */
    protected const KEY_IS_AVAILABLE = 'isAvaialble';

    /**
     * @var string
     */
    protected const KEY_SERVICE_POINT_UUID = 'servicePointUuid';

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
     * @param array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>> $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid
     *
     * @return array<string, array<int, array<string, mixed>>>
     */
    public function buildProductOfferAvailabilityDataPerServicePoint(
        array $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid
    ): array {
        $availabilityPerServicePoint = [];

        foreach ($productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid as $servicePointUuid => $productOfferServicePointAvailabilityResponseItemTransfers) {
            foreach ($productOfferServicePointAvailabilityResponseItemTransfers as $productOfferServicePointAvailabilityResponseItemTransfer) {
                $availabilityPerServicePoint[$servicePointUuid][] = $this->buildProductOfferAvailabilityData(
                    $productOfferServicePointAvailabilityResponseItemTransfer,
                    $productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailableOrFail(),
                );
            }
        }

        return $availabilityPerServicePoint;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer $productOfferServicePointAvailabilityResponseItemTransfer
     * @param bool $isAvailable
     *
     * @return array<string, mixed>
     */
    protected function buildProductOfferAvailabilityData(
        ProductOfferServicePointAvailabilityResponseItemTransfer $productOfferServicePointAvailabilityResponseItemTransfer,
        bool $isAvailable
    ): array {
        return [
            static::KEY_PRODUCT_OFFER_REFERENCE => $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference(),
            static::KEY_SERVICE_POINT_UUID => $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid(),
            static::KEY_IS_AVAILABLE => $isAvailable,
        ];
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
