<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Model;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToGlossaryStorageClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToLocaleClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToMessengerClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductBundleClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductStorageClientInterface;

class ItemFetcher implements ItemFetcherInterface
{
    /**
     * @var string
     */
    protected const MESSAGE_PARAM_SKU = '%sku%';

    /**
     * @var string
     */
    protected const PARAM_ITEMS = 'items';

    /**
     * @var string
     */
    protected const MESSAGE_INFO_RESTRICTED_PRODUCT_REMOVED = 'reorder.info.restricted-product.removed';

    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductBundleClientInterface
     */
    protected $productBundleClient;

    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToMessengerClientInterface
     */
    protected $messengerClient;

    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @var array<\SprykerShop\Yves\CustomerReorderWidgetExtension\Dependency\Plugin\ReorderItemExpanderPluginInterface>
     */
    protected $reorderItemExpanderPlugins;

    /**
     * @var array<\SprykerShop\Yves\CustomerReorderWidgetExtension\Dependency\Plugin\ReorderItemFetcherPluginInterface>
     */
    protected $reorderItemFetcherPlugins;

    /**
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductBundleClientInterface $productBundleClient
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductStorageClientInterface $productStorageClient
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToMessengerClientInterface $messengerClient
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToGlossaryStorageClientInterface $glossaryStorageClient
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToLocaleClientInterface $localeClient
     * @param array<\SprykerShop\Yves\CustomerReorderWidgetExtension\Dependency\Plugin\ReorderItemExpanderPluginInterface> $reorderItemExpanderPlugins
     * @param array<\SprykerShop\Yves\CustomerReorderWidgetExtension\Dependency\Plugin\ReorderItemFetcherPluginInterface> $reorderItemFetcherPlugins
     */
    public function __construct(
        CustomerReorderWidgetToProductBundleClientInterface $productBundleClient,
        CustomerReorderWidgetToProductStorageClientInterface $productStorageClient,
        CustomerReorderWidgetToMessengerClientInterface $messengerClient,
        CustomerReorderWidgetToGlossaryStorageClientInterface $glossaryStorageClient,
        CustomerReorderWidgetToLocaleClientInterface $localeClient,
        array $reorderItemExpanderPlugins,
        array $reorderItemFetcherPlugins
    ) {
        $this->productBundleClient = $productBundleClient;
        $this->productStorageClient = $productStorageClient;
        $this->messengerClient = $messengerClient;
        $this->glossaryStorageClient = $glossaryStorageClient;
        $this->localeClient = $localeClient;
        $this->reorderItemExpanderPlugins = $reorderItemExpanderPlugins;
        $this->reorderItemFetcherPlugins = $reorderItemFetcherPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function getAll(OrderTransfer $orderTransfer): array
    {
        $sourceItems = $this->getOrderItemsTransfer($orderTransfer);

        return $sourceItems;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array<mixed> $requestParams
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function getByIds(OrderTransfer $orderTransfer, array $requestParams): array
    {
        $orderItemIds = $requestParams[static::PARAM_ITEMS] ?? [];

        $sourceItemTransfers = $this->getOrderItemsTransfer($orderTransfer);
        $itemTransfers = $this->filterById($sourceItemTransfers, $orderItemIds);

        return array_merge(
            $itemTransfers,
            $this->executeReorderItemFetcherPlugins($sourceItemTransfers, $requestParams),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function getOrderItemsTransfer(OrderTransfer $orderTransfer): array
    {
        $itemTransfers = $this->productBundleClient
            ->getItemsWithBundlesItems(
                (new QuoteTransfer())
                    ->setItems($orderTransfer->getItems())
                    ->setBundleItems($orderTransfer->getBundleItems()),
            );
        $itemTransfers = $this->expandBundleItemsWithIdSalesOrderItem($orderTransfer, $itemTransfers);
        $itemTransfers = $this->executeReorderItemExpanderPlugins($itemTransfers, $orderTransfer);

        return $this->cleanUpItems($itemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function expandBundleItemsWithIdSalesOrderItem(OrderTransfer $orderTransfer, array $itemTransfers): array
    {
        $salesOrderItemIds = $this->getSalesOrderItemIdsForBundleItems($orderTransfer);

        foreach ($itemTransfers as $itemTransfer) {
            $bundleItemIdentifier = $itemTransfer->getBundleItemIdentifier();

            if ($bundleItemIdentifier) {
                $itemTransfer->setIdSalesOrderItem($salesOrderItemIds[$bundleItemIdentifier] ?? null);
            }
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array<int>
     */
    protected function getSalesOrderItemIdsForBundleItems(OrderTransfer $orderTransfer): array
    {
        $salesOrderItemIds = [];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $relatedBundleItemIdentifier = $itemTransfer->getRelatedBundleItemIdentifier();

            if ($relatedBundleItemIdentifier && !isset($salesOrderItemIds[$relatedBundleItemIdentifier])) {
                $salesOrderItemIds[$relatedBundleItemIdentifier] = $itemTransfer->getIdSalesOrderItem();
            }
        }

        return $salesOrderItemIds;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function cleanUpItems(array $itemTransfers): array
    {
        $cleanItems = [];
        foreach ($itemTransfers as $itemTransfer) {
            if ($this->productStorageClient->isProductConcreteRestricted($itemTransfer->getId())) {
                $this->addInfoMessage($itemTransfer);

                continue;
            }

            $idSaleOrderItem = $itemTransfer->getIdSalesOrderItem();
            $itemTransfer->setIdSalesOrderItem(null);
            $itemTransfer->setUuid(null);
            $itemTransfer->setIsOrdered(false);
            $itemTransfer = $this->cleanUpProductOptions($itemTransfer);
            $itemTransfer = $this->cleanUpItemShipmentAddress($itemTransfer);

            $cleanItems[$idSaleOrderItem] = $itemTransfer;
        }

        return $cleanItems;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addInfoMessage(ItemTransfer $itemTransfer): void
    {
        $translatedMessage = $this->glossaryStorageClient->translate(
            static::MESSAGE_INFO_RESTRICTED_PRODUCT_REMOVED,
            $this->localeClient->getCurrentLocale(),
            [static::MESSAGE_PARAM_SKU => $itemTransfer->getSku()],
        );

        $this->messengerClient->addInfoMessage($translatedMessage);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function cleanUpProductOptions(ItemTransfer $itemTransfer): ItemTransfer
    {
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $productOptionTransfer->setIsOrdered(false);
        }

        return $itemTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param array<int> $idOrderItems
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function filterById(array $itemTransfers, array $idOrderItems): array
    {
        $allowed_keys = array_flip($idOrderItems);
        $filteredItems = array_intersect_key($itemTransfers, $allowed_keys);

        return $filteredItems;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function cleanUpItemShipmentAddress(ItemTransfer $itemTransfer): ItemTransfer
    {
        $shipmentTransfer = $itemTransfer->getShipment();
        if ($shipmentTransfer === null) {
            return $itemTransfer;
        }

        $shippingAddressTransfer = $shipmentTransfer->getShippingAddress();
        if ($shippingAddressTransfer === null) {
            return $itemTransfer;
        }

        $shippingAddressTransfer->setIdSalesOrderAddress(null);

        return $itemTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function executeReorderItemExpanderPlugins(array $itemTransfers, OrderTransfer $orderTransfer): array
    {
        foreach ($this->reorderItemExpanderPlugins as $reorderItemExpanderPlugin) {
            $itemTransfers = $reorderItemExpanderPlugin->expand($itemTransfers, $orderTransfer);
        }

        return $itemTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param array<mixed> $requestParams
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function executeReorderItemFetcherPlugins(array $itemTransfers, array $requestParams): array
    {
        $fetchedItemTransfers = [];
        foreach ($this->reorderItemFetcherPlugins as $reorderItemFetcherPlugin) {
            $fetchedItemTransfers[] = $reorderItemFetcherPlugin->execute($itemTransfers, $requestParams);
        }

        return array_merge(...$fetchedItemTransfers);
    }
}
