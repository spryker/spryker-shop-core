<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\Handler;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToCartClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToQuoteClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToZedRequestClientInterface;
use SprykerShop\Yves\QuickOrderPage\ProductResolver\ProductResolverInterface;
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
     * @var \SprykerShop\Yves\QuickOrderPage\ProductResolver\ProductResolverInterface
     */
    protected $productResolver;

    /**
     * @var \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderItemExpanderPluginInterface[]
     */
    protected $itemExpanderPlugins;

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToCartClientInterface $cartClient
     * @param \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToQuoteClientInterface $quoteClient
     * @param \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToZedRequestClientInterface $zedRequestClient
     * @param \SprykerShop\Yves\QuickOrderPage\ProductResolver\ProductResolverInterface $productResolver
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderItemExpanderPluginInterface[] $itemExpanderPlugins
     */
    public function __construct(
        QuickOrderPageToCartClientInterface $cartClient,
        QuickOrderPageToQuoteClientInterface $quoteClient,
        QuickOrderPageToZedRequestClientInterface $zedRequestClient,
        ProductResolverInterface $productResolver,
        Request $request,
        array $itemExpanderPlugins
    ) {
        $this->cartClient = $cartClient;
        $this->quoteClient = $quoteClient;
        $this->zedRequestClient = $zedRequestClient;
        $this->productResolver = $productResolver;
        $this->request = $request;
        $this->itemExpanderPlugins = $itemExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return bool
     */
    public function addToCart(QuickOrderTransfer $quickOrderTransfer): bool
    {
        if (!$this->hasItems($quickOrderTransfer) || !$this->isValid($quickOrderTransfer)) {
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
        if (!$this->hasItems($quickOrderTransfer) || !$this->isValid($quickOrderTransfer)) {
            return false;
        }

        $this->clearQuote();

        return $this->addItemsToCart($quickOrderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return bool
     */
    protected function hasItems(QuickOrderTransfer $quickOrderTransfer): bool
    {
        return (bool)$quickOrderTransfer->getItems()->count();
    }

    /**
     * @return void
     */
    protected function clearQuote(): void
    {
        $this->quoteClient->setQuote(new QuoteTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return bool
     */
    protected function isValid(QuickOrderTransfer $quickOrderTransfer): bool
    {
        foreach ($quickOrderTransfer->getItems() as $quickOrderItemTransfer) {
            if ($quickOrderItemTransfer->getMessages()->count()) {
                return false;
            }
        }

        return true;
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
            $itemTransfer->setGroupKeyPrefix(uniqid('', true));
            $itemTransfers[$key] = $this->expandItemTransfer($itemTransfer);
        }

        $this->cartClient->addItems($itemTransfers, $this->request->request->all());
        $this->zedRequestClient->addFlashMessagesFromLastZedRequest();

        $errorMessageCount = count($this->zedRequestClient->getLastResponseErrorMessages());

        return $errorMessageCount < 1;
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

            $itemTransfers[] = (new ItemTransfer())
                ->setSku($sku)
                ->setQuantity($quantity);
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function expandItemTransfer(ItemTransfer $itemTransfer): ItemTransfer
    {
        $itemTransfer = $this->productResolver->expandItemTransferWithProductIds($itemTransfer);

        foreach ($this->itemExpanderPlugins as $itemExpanderPlugin) {
            $itemTransfer = $itemExpanderPlugin->expandItem($itemTransfer);
        }

        return $itemTransfer;
    }
}
