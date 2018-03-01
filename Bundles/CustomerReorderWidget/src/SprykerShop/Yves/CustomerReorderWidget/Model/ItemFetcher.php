<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Model;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductBundleClientInterface;

class ItemFetcher implements ItemFetcherInterface
{
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
        $sourceItems = $this->getOrderItemsTransfer($orderTransfer);

        return $sourceItems;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int[] $idOrderItems
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getByIds(OrderTransfer $orderTransfer, array $idOrderItems): array
    {
        $sourceItems = $this->getOrderItemsTransfer($orderTransfer);
        $filteredItems = $this->filterById($sourceItems, $idOrderItems);

        return $filteredItems;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getOrderItemsTransfer(OrderTransfer $orderTransfer): array
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setItems($orderTransfer->getItems());
        $quoteTransfer->setBundleItems($orderTransfer->getBundleItems());
        $items = $this->productBundleClient
            ->getItemsWithBundlesItems($quoteTransfer);

        return $this->dropIds($items);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function dropIds(array $items): array
    {
        $cleanItems = [];
        foreach ($items as $item) {
            $idSaleOrderItem = $item->getIdSalesOrderItem();
            $item->setIdSalesOrderItem(null);
            $cleanItems[$idSaleOrderItem] = $item;
        }
        return $cleanItems;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
     * @param \Generated\Shared\Transfer\ItemTransfer[] $idOrderItems
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function filterById(array $items, array $idOrderItems): array
    {
        $allowed_keys = array_flip($idOrderItems);
        $filteredItems = array_intersect_key($items, $allowed_keys);

        return $filteredItems;
    }
}
