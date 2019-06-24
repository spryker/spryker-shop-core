<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Model;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToAvailabilityStorageClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Service\CustomerReorderWidgetToUtilQuantityServiceInterface;

class CartFiller implements CartFillerInterface
{
    protected const PARAM_ORDER_REFERENCE = 'orderReference';

    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Model\ItemFetcherInterface
     */
    protected $itemsFetcher;

    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToAvailabilityStorageClientInterface
     */
    protected $availabilityStorageClient;

    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Service\CustomerReorderWidgetToUtilQuantityServiceInterface
     */
    protected $utilQuantityService;

    /**
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface $cartClient
     * @param \SprykerShop\Yves\CustomerReorderWidget\Model\ItemFetcherInterface $itemsFetcher
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToAvailabilityStorageClientInterface $availabilityStorageClient
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Service\CustomerReorderWidgetToUtilQuantityServiceInterface $utilQuantityService
     */
    public function __construct(
        CustomerReorderWidgetToCartClientInterface $cartClient,
        ItemFetcherInterface $itemsFetcher,
        CustomerReorderWidgetToAvailabilityStorageClientInterface $availabilityStorageClient,
        CustomerReorderWidgetToUtilQuantityServiceInterface $utilQuantityService
    ) {
        $this->cartClient = $cartClient;
        $this->itemsFetcher = $itemsFetcher;
        $this->availabilityStorageClient = $availabilityStorageClient;
        $this->utilQuantityService = $utilQuantityService;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function fillFromOrder(OrderTransfer $orderTransfer): void
    {
        $orderTransfer = $this->groupAllOrderItemsBySku($orderTransfer);
        $items = $this->itemsFetcher->getAll($orderTransfer);

        $this->updateCart($items, $orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function groupAllOrderItemsBySku(OrderTransfer $orderTransfer): OrderTransfer
    {
        $groupedOrderItems = $this->groupItemsByGroupKey($orderTransfer->getItems());
        $orderTransfer->setItems(new ArrayObject($groupedOrderItems));

        return $orderTransfer;
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return float
     */
    protected function sumQuantities(float $firstQuantity, float $secondQuantity): float
    {
        return $this->utilQuantityService->sumQuantities($firstQuantity, $secondQuantity);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int[] $idOrderItems
     *
     * @return void
     */
    public function fillSelectedFromOrder(OrderTransfer $orderTransfer, array $idOrderItems): void
    {
        $items = $this->itemsFetcher->getByIds($orderTransfer, $idOrderItems);
        $items = $this->groupItemsByGroupKey($items);

        $this->updateCart($items, $orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItems
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function groupItemsByGroupKey(iterable $orderItems): array
    {
        $groupedOrderItems = [];

        foreach ($orderItems as $itemTransfer) {
            if (!array_key_exists($itemTransfer->getGroupKey(), $groupedOrderItems)) {
                $groupedOrderItems[$itemTransfer->getGroupKey()] = $itemTransfer;
                continue;
            }

            $newQuantity = $this->sumQuantities(
                $groupedOrderItems[$itemTransfer->getGroupKey()]->getQuantity(),
                $itemTransfer->getQuantity()
            );
            $groupedOrderItems[$itemTransfer->getGroupKey()]->setQuantity($newQuantity);
        }

        return $groupedOrderItems;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItems
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function updateCart(array $orderItems, OrderTransfer $orderTransfer): void
    {
        $this->updateItemsQuantity($orderItems);

        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setQuote($this->cartClient->getQuote());
        $orderItemsObject = $this->createOrderItemsObject($orderItems);
        $cartChangeTransfer->setItems($orderItemsObject);

        $this->cartClient->addValidItems($cartChangeTransfer, [static::PARAM_ORDER_REFERENCE => $orderTransfer->getOrderReference()]);
    }

    /**
     * @param array $orderItems
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]|ArrayObject
     */
    protected function createOrderItemsObject(array $orderItems): ArrayObject
    {
        $orderItemsObject = new ArrayObject();
        foreach ($orderItems as $itemTransfer) {
            $orderItemsObject->append($this->removeIdSalesShipmentFromItem($itemTransfer));
        }

        return $orderItemsObject;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function removeIdSalesShipmentFromItem(ItemTransfer $itemTransfer): ItemTransfer
    {
        if ($itemTransfer->getShipment() === null) {
            return $itemTransfer;
        }

        $itemTransfer->getShipment()->setIdSalesShipment(null);

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItems
     *
     * @return void
     */
    protected function updateItemsQuantity(array $orderItems): void
    {
        foreach ($orderItems as $item) {
            $spyAvailabilityAbstractTransfer = $this->getAvailabilityAbstractByItemTransfer($item);

            foreach ($spyAvailabilityAbstractTransfer->getSpyAvailabilities() as $spyAvailability) {
                if ($spyAvailability->getSku() !== $item->getSku()) {
                    continue;
                }

                if ($spyAvailability->getIsNeverOutOfStock()) {
                    continue;
                }

                if ($this->utilQuantityService->isQuantityEqual((float)$spyAvailability->getQuantity(), 0)) {
                    continue;
                }

                if ($this->isQuantityGreaterOrEqual((float)$spyAvailability->getQuantity(), (float)$item->getQuantity())) {
                    continue;
                }

                $item->setQuantity((float)$spyAvailability->getQuantity());
            }
        }
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return bool
     */
    protected function isQuantityGreaterOrEqual(float $firstQuantity, float $secondQuantity): bool
    {
        return $this->utilQuantityService->isQuantityGreaterOrEqual($firstQuantity, $secondQuantity);
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return bool
     */
    protected function isQuantityEqual(float $firstQuantity, float $secondQuantity): bool
    {
        return $this->utilQuantityService->isQuantityEqual($firstQuantity, $secondQuantity);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer
     */
    protected function getAvailabilityAbstractByItemTransfer(ItemTransfer $itemTransfer): SpyAvailabilityAbstractEntityTransfer
    {
        $itemTransfer->requireIdProductAbstract();

        return $this->availabilityStorageClient->getAvailabilityAbstract($itemTransfer->getIdProductAbstract());
    }
}
