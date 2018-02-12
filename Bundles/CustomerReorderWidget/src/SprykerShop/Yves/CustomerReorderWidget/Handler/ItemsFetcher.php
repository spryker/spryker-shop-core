<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Handler;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductBundleClientInterface;

class ItemsFetcher implements ItemsFetcherInterface
{
    /**
     * Name of field in grouped items.
     * @see \Spryker\Client\ProductBundle\Grouper\ProductBundleGrouper::BUNDLE_PRODUCT
     */
    public const BUNDLE_PRODUCT = 'bundleProduct';

    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductBundleClientInterface
     */
    protected $productBundleClient;

    /**
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductBundleClientInterface $productBundleClient
     */
    public function __construct(
        CustomerReorderWidgetToProductBundleClientInterface $productBundleClient
    ) {
        $this->productBundleClient = $productBundleClient;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getAll(OrderTransfer $orderTransfer): array
    {
        $rawItems = $this->getOrderItemsTransfer($orderTransfer);
        $reducedItems = $this->reduceItemsBySku($rawItems);

        return $reducedItems;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int[] $idOrderItems
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getByIds(OrderTransfer $orderTransfer, array $idOrderItems): array
    {
        $rawItems = $this->getOrderItemsTransfer($orderTransfer);
        $filteredItems = $this->filterById($rawItems, $idOrderItems);
        $reducedItems = $this->reduceItemsBySku($filteredItems);

        return $reducedItems;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getOrderItemsTransfer(OrderTransfer $orderTransfer): array
    {
        $items = $this->productBundleClient
            ->getGroupedBundleItems($orderTransfer->getItems(), $orderTransfer->getBundleItems());
        $items = $this->getProductsFromBundles($items);

        return $items;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $groupedItems
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getProductsFromBundles(array $groupedItems): array
    {
        $items = array_map(function ($groupedItem) {
            return $groupedItem instanceof ItemTransfer ? $groupedItem : $groupedItem[static::BUNDLE_PRODUCT];
        }, $groupedItems);

        return $items;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
     * @param \Generated\Shared\Transfer\ItemTransfer[] $idOrderItems
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function filterById(array $items, array $idOrderItems): array
    {
        $filteredItems = [];
        foreach ($items as $item) {
            if (in_array($item->getId(), $idOrderItems)) {
                $filteredItems[] = $item;
            }
        }

        return $filteredItems;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemsTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function reduceItemsBySku(array $itemsTransfer): array
    {
        /** @var array|\Generated\Shared\Transfer\ItemTransfer[] $newItems */
        $newItems = [];
        foreach ($itemsTransfer as $itemTransfer) {
            if (!array_key_exists($itemTransfer->getSku(), $newItems)) {
                $newItems[$itemTransfer->getSku()] = $itemTransfer;
                continue;
            }

            $currentItem = $newItems[$itemTransfer->getSku()];
            $currentItem->setQuantity($currentItem->getQuantity() + $itemTransfer->getQuantity());
        }

        return $newItems;
    }
}
