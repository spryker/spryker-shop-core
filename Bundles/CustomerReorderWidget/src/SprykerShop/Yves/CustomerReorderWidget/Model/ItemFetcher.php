<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Model;

use ArrayObject;
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

        return $this->cleanUpItems($items);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function cleanUpItems(array $items): array
    {
        $cleanItems = [];

        foreach ($items as $item) {
            $idSaleOrderItem = $item->getIdSalesOrderItem();
            $item->setIdSalesOrderItem(null);
            $item->setIsOrdered(false);
            $cleanProductOptions = $this->cleanUpProductOptions($item->getProductOptions());
            $item->setProductOptions($cleanProductOptions);

            $cleanItems[$idSaleOrderItem] = $item;
        }

        return $cleanItems;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductOptionTransfer[] $productOptions
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductOptionTransfer[]
     */
    protected function cleanUpProductOptions(ArrayObject $productOptions): ArrayObject
    {
        $cleanProductOptions = [];

        foreach ($productOptions as $productOption) {
            $productOption->setIsOrdered(false);
            $cleanProductOptions[] = $productOption;
        }

        return new ArrayObject($cleanProductOptions);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
     * @param int[] $idOrderItems
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
