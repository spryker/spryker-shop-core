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
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface;

class CartFiller implements CartFillerInterface
{
    /**
     * @var string
     */
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
     * @var array<\SprykerShop\Yves\CustomerReorderWidgetExtension\Dependency\Plugin\PostReorderPluginInterface>
     */
    protected $postReorderPlugins;

    /**
     * @var array<\SprykerShop\Yves\CustomerReorderWidgetExtension\Dependency\Plugin\ReorderItemSanitizerPluginInterface>
     */
    protected $reorderItemSanitizerPlugins;

    /**
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface $cartClient
     * @param \SprykerShop\Yves\CustomerReorderWidget\Model\ItemFetcherInterface $itemsFetcher
     * @param array<\SprykerShop\Yves\CustomerReorderWidgetExtension\Dependency\Plugin\PostReorderPluginInterface> $postReorderPlugins
     * @param array<\SprykerShop\Yves\CustomerReorderWidgetExtension\Dependency\Plugin\ReorderItemSanitizerPluginInterface> $reorderItemSanitizerPlugins
     */
    public function __construct(
        CustomerReorderWidgetToCartClientInterface $cartClient,
        ItemFetcherInterface $itemsFetcher,
        array $postReorderPlugins,
        array $reorderItemSanitizerPlugins
    ) {
        $this->cartClient = $cartClient;
        $this->itemsFetcher = $itemsFetcher;
        $this->postReorderPlugins = $postReorderPlugins;
        $this->reorderItemSanitizerPlugins = $reorderItemSanitizerPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function fillFromOrder(OrderTransfer $orderTransfer): void
    {
        $items = $this->itemsFetcher->getAll($orderTransfer);

        $this->updateCart($items, $orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array<mixed> $requestParams
     *
     * @return void
     */
    public function fillSelectedFromOrder(OrderTransfer $orderTransfer, array $requestParams): void
    {
        $items = $this->itemsFetcher->getByIds($orderTransfer, $requestParams);

        $this->updateCart($items, $orderTransfer);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $orderItems
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function updateCart(array $orderItems, OrderTransfer $orderTransfer): void
    {
        $orderItemsBeforeSanitize = $this->copyItemTransfers($orderItems);
        $cartChangeTransfer = $this->createCartChangeTransfer($orderItems);
        $cartChangeTransfer->setQuote($this->cartClient->getQuote());
        $orderItemTransfers = $this->sanitizeOrderItems($orderItems);
        $cartChangeTransfer->setItems($orderItemTransfers);

        $quoteTransfer = $this->cartClient->addValidItems($cartChangeTransfer, [
            static::PARAM_ORDER_REFERENCE => $orderTransfer->getOrderReference(),
        ]);

        $this->executePostReorderPlugins($quoteTransfer, $orderItemsBeforeSanitize);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function copyItemTransfers(array $itemTransfers): array
    {
        $copiedItemTransfers = [];

        foreach ($itemTransfers as $itemTransfer) {
            $copiedItemTransfers[] = (new ItemTransfer())->fromArray($itemTransfer->toArray(false));
        }

        return $copiedItemTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $orderItems
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function sanitizeOrderItems(array $orderItems): ArrayObject
    {
        foreach ($orderItems as $itemTransfer) {
            $itemTransfer = $this->removeIdSalesShipmentFromItem($itemTransfer);
            $itemTransfer = $this->removeSalesOrderConfiguredBundleItemFromItemTransfer($itemTransfer);
            $itemTransfer = $this->removeStateFromItem($itemTransfer);
            $this->removeSalesOrderItemConfigurationFromItem($itemTransfer);
        }

        return new ArrayObject($this->executeReorderItemSanitizerPlugins($orderItems));
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
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $orderItems
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransfer(array $orderItems): CartChangeTransfer
    {
        return (new CartChangeTransfer())
            ->setQuote($this->cartClient->getQuote())
            ->setItems(new ArrayObject($orderItems));
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function removeSalesOrderConfiguredBundleItemFromItemTransfer(ItemTransfer $itemTransfer): ItemTransfer
    {
        if (!$itemTransfer->getSalesOrderConfiguredBundleItem()) {
            return $itemTransfer;
        }

        $itemTransfer->setSalesOrderConfiguredBundleItem(null);

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function removeStateFromItem(ItemTransfer $itemTransfer): ItemTransfer
    {
        if (!$itemTransfer->getState()) {
            return $itemTransfer;
        }

        $itemTransfer->setState(null);

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function removeSalesOrderItemConfigurationFromItem(ItemTransfer $itemTransfer): ItemTransfer
    {
        if (!$itemTransfer->getSalesOrderItemConfiguration()) {
            return $itemTransfer;
        }

        $itemTransfer->setSalesOrderItemConfiguration(null);

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return void
     */
    protected function executePostReorderPlugins(QuoteTransfer $quoteTransfer, array $itemTransfers): void
    {
        foreach ($this->postReorderPlugins as $postReorderPlugin) {
            $postReorderPlugin->execute($quoteTransfer, $itemTransfers);
        }
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function executeReorderItemSanitizerPlugins(array $itemTransfers): array
    {
        foreach ($this->reorderItemSanitizerPlugins as $reorderItemSanitizerPlugin) {
            $itemTransfers = $reorderItemSanitizerPlugin->execute($itemTransfers);
        }

        return $itemTransfers;
    }
}
