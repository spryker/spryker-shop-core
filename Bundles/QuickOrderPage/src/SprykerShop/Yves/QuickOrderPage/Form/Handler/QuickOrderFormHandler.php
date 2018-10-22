<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\Handler;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToCartClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToQuoteClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToZedRequestClientInterface;
use Symfony\Component\HttpFoundation\Request;

class QuickOrderFormHandler implements QuickOrderFormHandlerInterface
{
    /**
     * @var \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderItemExpanderPluginInterface[]
     */
    protected $itemExpanderPlugins;

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToCartClientInterface $cartClient
     * @param \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToQuoteClientInterface $quoteClient
     * @param \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToZedRequestClientInterface $zedRequestClient
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderItemExpanderPluginInterface[] $itemExpanderPlugins
     */
    public function __construct(
        QuickOrderPageToCartClientInterface $cartClient,
        QuickOrderPageToQuoteClientInterface $quoteClient,
        QuickOrderPageToZedRequestClientInterface $zedRequestClient,
        Request $request,
        array $itemExpanderPlugins
    ) {
        $this->cartClient = $cartClient;
        $this->zedRequestClient = $zedRequestClient;
        $this->request = $request;
        $this->quoteClient = $quoteClient;
        $this->itemExpanderPlugins = $itemExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return bool
     */
    public function addToCart(QuickOrderTransfer $quickOrderTransfer): bool
    {
        if (!$this->hasItems($quickOrderTransfer)) {
            return false;
        }

        return $this->addItemsToCart($quickOrderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return bool
     */
    public function addToEmptyCart(QuickOrderTransfer $quickOrderTransfer): bool
    {
        if (!$this->hasItems($quickOrderTransfer)) {
            return false;
        }

        $this->quoteClient->clearQuote();

        return $this->addToCart($quickOrderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrder
     *
     * @return bool
     */
    protected function hasItems(QuickOrderTransfer $quickOrder): bool
    {
        return (bool)$quickOrder->getItems()->count();
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return bool
     */
    protected function addItemsToCart(QuickOrderTransfer $quickOrderTransfer): bool
    {
        $itemTransfers = $this->mapToItemTransfers($quickOrderTransfer);
        foreach ($itemTransfers as $key => $itemTransfer) {
            $itemTransfers[$key] = $this->expandItemTransfer($itemTransfer);
        }

        $this->cartClient->addItems($itemTransfers, $this->request->request->all());
        $this->zedRequestClient->addFlashMessagesFromLastZedRequest();

        $errorMessageCount = count($this->zedRequestClient->getLastResponseErrorMessages());
        $isSuccess = (bool)($errorMessageCount < 1);

        return $isSuccess;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrder
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function mapToItemTransfers(QuickOrderTransfer $quickOrder): array
    {
        /** @var \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers */
        $itemTransfers = [];
        $quickOrderItemTransfers = $quickOrder->getItems();
        foreach ($quickOrderItemTransfers as $quickOrderItemTransfer) {
            $sku = $quickOrderItemTransfer->getSku();
            if (!$sku) {
                continue;
            }

            $quantity = $quickOrderItemTransfer->getQuantity();
            if (!$quantity) {
                continue;
            }

            if (!isset($itemTransfers[$sku])) {
                $itemTransfers[$sku] = (new ItemTransfer())
                    ->setSku($quickOrderItemTransfer->getSku())
                    ->setQuantity(0);
            }

            $itemTransfers[$sku]->setQuantity($quantity + $itemTransfers[$sku]->getQuantity());
        }

        return array_values($itemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function expandItemTransfer(ItemTransfer $itemTransfer): ItemTransfer
    {
        foreach ($this->itemExpanderPlugins as $itemExpanderPlugin) {
            $itemTransfer = $itemExpanderPlugin->expandItem($itemTransfer);
        }

        return $itemTransfer;
    }
}
