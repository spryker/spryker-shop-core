<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityRequestItemTransfer;
use SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Builder\ServicePointAvailabilityMessageBuilderInterface;
use SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Dependency\Client\ProductOfferServicePointAvailabilityWidgetToProductOfferServicePointAvailabilityCalculatorStorageClientInterface;

class ProductOfferServicePointAvailabilityReader implements ProductOfferServicePointAvailabilityReaderInterface
{
    /**
     * @var \SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Reader\QuoteItemReaderInterface
     */
    protected QuoteItemReaderInterface $quoteItemReader;

    /**
     * @var \SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Builder\ServicePointAvailabilityMessageBuilderInterface
     */
    protected ServicePointAvailabilityMessageBuilderInterface $servicePointAvailabilityMessageBuilder;

    /**
     * @var \SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Dependency\Client\ProductOfferServicePointAvailabilityWidgetToProductOfferServicePointAvailabilityCalculatorStorageClientInterface
     */
    protected ProductOfferServicePointAvailabilityWidgetToProductOfferServicePointAvailabilityCalculatorStorageClientInterface $productOfferServicePointAvailabilityCalculatorStorageClient;

    /**
     * @param \SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Reader\QuoteItemReaderInterface $quoteItemReader
     * @param \SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Builder\ServicePointAvailabilityMessageBuilderInterface $servicePointAvailabilityMessageBuilder
     * @param \SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Dependency\Client\ProductOfferServicePointAvailabilityWidgetToProductOfferServicePointAvailabilityCalculatorStorageClientInterface $productOfferServicePointAvailabilityCalculatorStorageClient
     */
    public function __construct(
        QuoteItemReaderInterface $quoteItemReader,
        ServicePointAvailabilityMessageBuilderInterface $servicePointAvailabilityMessageBuilder,
        ProductOfferServicePointAvailabilityWidgetToProductOfferServicePointAvailabilityCalculatorStorageClientInterface $productOfferServicePointAvailabilityCalculatorStorageClient
    ) {
        $this->quoteItemReader = $quoteItemReader;
        $this->servicePointAvailabilityMessageBuilder = $servicePointAvailabilityMessageBuilder;
        $this->productOfferServicePointAvailabilityCalculatorStorageClient = $productOfferServicePointAvailabilityCalculatorStorageClient;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ServicePointSearchTransfer> $servicePointSearchTransfers
     * @param list<string> $groupKeys
     * @param string|null $serviceTypeUuid
     * @param string|null $shipmentTypeUuid
     *
     * @return array<string, string>
     */
    public function getProductOfferServicePointAvailabilities(
        array $servicePointSearchTransfers,
        array $groupKeys,
        ?string $serviceTypeUuid = null,
        ?string $shipmentTypeUuid = null
    ): array {
        if (!$servicePointSearchTransfers) {
            return $this->servicePointAvailabilityMessageBuilder->buildUnavailableAvailabilityMessagesPerServicePoint(
                $servicePointSearchTransfers,
            );
        }

        $itemTransfersBeforeFiltering = $this->quoteItemReader->getItemsFromQuote($groupKeys);
        $itemTransfers = $this->filterOutItemsWithoutProductOfferReference($itemTransfersBeforeFiltering);

        $hasItemsWithoutProductOfferReferences = count($itemTransfersBeforeFiltering) > count($itemTransfers);

        if (!$itemTransfers) {
            return $this->servicePointAvailabilityMessageBuilder->buildUnavailableAvailabilityMessagesPerServicePoint(
                $servicePointSearchTransfers,
            );
        }

        $productOfferServicePointAvailabilityRequestItemTransfers = $this->mapItemTransfersToProductOfferServicePointAvailabilityRequestItemTransfers(
            $itemTransfers,
        );

        $productOfferServicePointAvailabilityCriteriaTransfer = $this->createProductOfferServicePointAvailabilityCriteriaTransfer(
            $productOfferServicePointAvailabilityRequestItemTransfers,
            $this->extractUuidsFromServicePointSearchTransfers($servicePointSearchTransfers),
            $serviceTypeUuid,
            $shipmentTypeUuid,
        );

        $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid = $this->productOfferServicePointAvailabilityCalculatorStorageClient
            ->calculateProductOfferServicePointAvailabilities($productOfferServicePointAvailabilityCriteriaTransfer);

        return $this->servicePointAvailabilityMessageBuilder->buildAvailabilityMessagesPerServicePoint(
            $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid,
            $hasItemsWithoutProductOfferReferences,
        );
    }

    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function filterOutItemsWithoutProductOfferReference(array $itemTransfers): array
    {
        $itemTransfersWithProductOfferReferences = [];

        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getProductOfferReference()) {
                $itemTransfersWithProductOfferReferences[] = $itemTransfer;
            }
        }

        return $itemTransfersWithProductOfferReferences;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityRequestItemTransfer>
     */
    protected function mapItemTransfersToProductOfferServicePointAvailabilityRequestItemTransfers(
        array $itemTransfers
    ): array {
        $productOfferServicePointAvailabilityRequestItemTransfers = [];

        foreach ($itemTransfers as $itemTransfer) {
            $productOfferServicePointAvailabilityRequestItemTransfers[] = (new ProductOfferServicePointAvailabilityRequestItemTransfer())
                ->fromArray($itemTransfer->toArray(), true)
                ->setProductConcreteSku($itemTransfer->getSku());
        }

        return $productOfferServicePointAvailabilityRequestItemTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ServicePointSearchTransfer> $servicePointSearchTransfers
     *
     * @return list<string>
     */
    protected function extractUuidsFromServicePointSearchTransfers(array $servicePointSearchTransfers): array
    {
        $uuids = [];

        foreach ($servicePointSearchTransfers as $servicePointSearchTransfer) {
            $uuids[] = $servicePointSearchTransfer->getUuidOrFail();
        }

        return $uuids;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityRequestItemTransfer> $productOfferServicePointAvailabilityRequestItemTransfers
     * @param list<string> $servicePointUuids
     * @param string|null $serviceTypeUuid
     * @param string|null $shipmentTypeUuid
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer
     */
    protected function createProductOfferServicePointAvailabilityCriteriaTransfer(
        array $productOfferServicePointAvailabilityRequestItemTransfers,
        array $servicePointUuids,
        ?string $serviceTypeUuid = null,
        ?string $shipmentTypeUuid = null
    ): ProductOfferServicePointAvailabilityCriteriaTransfer {
        return (new ProductOfferServicePointAvailabilityCriteriaTransfer())->setProductOfferServicePointAvailabilityConditions(
            (new ProductOfferServicePointAvailabilityConditionsTransfer())
                ->setServiceTypeUuid($serviceTypeUuid)
                ->setShipmentTypeUuid($shipmentTypeUuid)
                ->setServicePointUuids($servicePointUuids)
                ->setProductOfferServicePointAvailabilityRequestItems(
                    new ArrayObject($productOfferServicePointAvailabilityRequestItemTransfers),
                ),
        );
    }
}
