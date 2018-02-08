<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Handler;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductBundleClientInterface;

class ReorderHandler implements ReorderHandlerInterface
{
    /**
     * Name of field in grouped items.
     * Softlink to ProductBundle
     * @see \Spryker\Client\ProductBundle\Grouper\ProductBundleGrouper::BUNDLE_PRODUCT
     */
    public const BUNDLE_PRODUCT = 'bundleProduct';

    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductBundleClientInterface
     */
    protected $productBundleClient;

    /**
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface $cartClient
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductBundleClientInterface $productBundleClient
     */
    public function __construct(
        CustomerReorderWidgetToCartClientInterface $cartClient,
        CustomerReorderWidgetToProductBundleClientInterface $productBundleClient
    ) {
        $this->cartClient = $cartClient;
        $this->productBundleClient = $productBundleClient;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function reorder(OrderTransfer $orderTransfer): void
    {
        $items = $this->getOrderItemsTransfer($orderTransfer);

        $this->updateCart($items);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int[] $idOrderItems
     *
     * @return void
     */
    public function reorderItems(OrderTransfer $orderTransfer, array $idOrderItems): void
    {
        $items = $this->getOrderItemsTransfer($orderTransfer);

        $itemsToAdd = [];
        foreach ($items as $item) {
            if (!$idOrderItems) {
                break;
            }
            if (!in_array($item->getId(), $idOrderItems)) {
                continue;
            }

            $itemsToAdd[] = $item;
            $key = array_search($item->getId(), $idOrderItems);
            unset($idOrderItems[$key]);
        }

        //if (!empty($idOrderItems)) show error

        $this->updateCart($itemsToAdd);
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
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItems
     *
     * @return void
     */
    protected function updateCart(array $orderItems): void
    {
        $quote = new QuoteTransfer();
        $this->cartClient->storeQuote($quote);

        $quoteTransfer = $this->cartClient->addItems($orderItems);
        $this->cartClient->storeQuote($quoteTransfer);
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
}
