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
use Symfony\Component\HttpFoundation\Request;

class QuickOrderFormOperationHandler implements QuickOrderFormOperationHandlerInterface
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
     * @param \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToCartClientInterface $cartClient
     * @param \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToQuoteClientInterface $quoteClient
     * @param \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToZedRequestClientInterface $zedRequestClient
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct(
        QuickOrderPageToCartClientInterface $cartClient,
        QuickOrderPageToQuoteClientInterface $quoteClient,
        QuickOrderPageToZedRequestClientInterface $zedRequestClient,
        Request $request
    ) {
        $this->cartClient = $cartClient;
        $this->zedRequestClient = $zedRequestClient;
        $this->request = $request;
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrder
     *
     * @return bool
     */
    public function addToCart(QuickOrderTransfer $quickOrder): bool
    {
        $itemTransfers = $this->getItemTransfers($quickOrder);

        if ($itemTransfers) {
            return $this->addItemsToCart($itemTransfers);
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrder
     *
     * @return bool
     */
    public function createOrder(QuickOrderTransfer $quickOrder): bool
    {
        $itemTransfers = $this->getItemTransfers($quickOrder);

        if ($itemTransfers) {
            $this->clearQuote();

            return $this->addItemsToCart($itemTransfers);
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return bool
     */
    protected function addItemsToCart(array $itemTransfers): bool
    {
        $this->cartClient->addItems($itemTransfers, $this->request->request->all());
        $this->zedRequestClient->addAllResponseMessagesToMessenger();

        return !(count($this->zedRequestClient->getResponsesErrorMessages()) > 0);
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrder
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getItemTransfers(QuickOrderTransfer $quickOrder): array
    {
        $itemTransfers = [];
        $quickOrderItemTransfers = $quickOrder->getItems();

        foreach ($quickOrderItemTransfers as $quickOrderItemTransfer) {
            if ($quickOrderItemTransfer->getSku() && $quickOrderItemTransfer->getQty()) {
                if (isset($itemTransfers[$quickOrderItemTransfer->getSku()])) {
                    $itemTransfer = $itemTransfers[$quickOrderItemTransfer->getSku()];
                    $itemTransfer->setQuantity($itemTransfer->getQuantity() + $quickOrderItemTransfer->getQty());
                    continue;
                }

                $itemTransfer = (new ItemTransfer())
                    ->setSku($quickOrderItemTransfer->getSku())
                    ->setQuantity($quickOrderItemTransfer->getQty());

                $itemTransfers[$itemTransfer->getSku()] = $itemTransfer;
            }
        }

        return array_values($itemTransfers);
    }

    /**
     * @return void
     */
    protected function clearQuote(): void
    {
        $this->quoteClient->setQuote(new QuoteTransfer());
    }
}
