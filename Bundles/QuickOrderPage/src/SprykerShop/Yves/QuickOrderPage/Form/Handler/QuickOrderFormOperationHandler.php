<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\Handler;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;
use Spryker\Client\Cart\Zed\CartStubInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToCartClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToMessengerClientInterface;

class QuickOrderFormOperationHandler implements QuickOrderFormOperationHandlerInterface
{
    /**
     * @var \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToMessengerClientInterface
     */
    protected $messengerClient;

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToCartClientInterface $cartClient
     * @param \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToMessengerClientInterface $messengerClient
     */
    public function __construct(
        QuickOrderPageToCartClientInterface $cartClient,
        QuickOrderPageToMessengerClientInterface $messengerClient
    ) {
        $this->cartClient = $cartClient;
        $this->messengerClient = $messengerClient;
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
            $this->cartClient->clearQuote();

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
        $quoteTransfer = $this->cartClient->addItems($itemTransfers);
        $zedStub = $this->cartClient->getZedStub();
        $this->setFlashMessages($zedStub);

        if (count($zedStub->getErrorMessages()) > 0) {
            return false;
        }

        $this->cartClient->storeQuote($quoteTransfer);

        return true;
    }

    /**
     * @param \Spryker\Client\Cart\Zed\CartStubInterface $zedStub
     *
     * @return void
     */
    protected function setFlashMessages(CartStubInterface $zedStub): void
    {
        foreach ($zedStub->getErrorMessages() as $errorMessage) {
            $this->messengerClient->addErrorMessage($errorMessage->getValue());
        }

        foreach ($zedStub->getInfoMessages() as $infoMessage) {
            $this->messengerClient->addInfoMessage($infoMessage->getValue());
        }

        foreach ($zedStub->getSuccessMessages() as $successMessage) {
            $this->messengerClient->addSuccessMessage($successMessage->getValue());
        }
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
}
