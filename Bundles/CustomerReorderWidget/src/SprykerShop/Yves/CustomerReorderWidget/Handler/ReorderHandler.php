<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Handler;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductBundleClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToSalesClientInterface;

class ReorderHandler implements ReorderHandlerInterface
{
    /**
     * Name of field in grouped items.
     * Softlink to ProductBundle
     * @see \Spryker\Client\ProductBundle\Grouper\ProductBundleGrouper::BUNDLE_PRODUCT
     */
    const BUNDLE_PRODUCT = 'bundleProduct';
    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToSalesClientInterface
     */
    protected $salesClient;

    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductBundleClientInterface
     */
    protected $productBundleClient;

    /**
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface $cartClient
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToSalesClientInterface $salesClient
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductBundleClientInterface $productBundleClient
     */
    public function __construct(
        CustomerReorderWidgetToCartClientInterface $cartClient,
        CustomerReorderWidgetToSalesClientInterface $salesClient,
        CustomerReorderWidgetToProductBundleClientInterface $productBundleClient
    ) {
        $this->cartClient = $cartClient;
        $this->salesClient = $salesClient;
        $this->productBundleClient = $productBundleClient;
    }

    /**
     * @param int $idSalesOrder
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function reorder(int $idSalesOrder, CustomerTransfer $customerTransfer): void
    {
        $items = $this->getOrderItemsTransfer($idSalesOrder, $customerTransfer);

        $this->updateCart($items);
    }

    /**
     * @param int $idSalesOrder
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param int[] $idOrderItems
     *
     * @return void
     */
    public function reorderItems(int $idSalesOrder, CustomerTransfer $customerTransfer, array $idOrderItems): void
    {
        $items = $this->getOrderItemsTransfer($idSalesOrder, $customerTransfer);

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
     * @param int $idSalesOrder
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getOrderItemsTransfer(int $idSalesOrder, CustomerTransfer $customerTransfer): array
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer
            ->setIdSalesOrder($idSalesOrder)
            ->setFkCustomer($customerTransfer->getIdCustomer());

        $orderTransfer = $this->salesClient
            ->getOrderDetails($orderTransfer);

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
    protected function updateCart(array $orderItems)
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
