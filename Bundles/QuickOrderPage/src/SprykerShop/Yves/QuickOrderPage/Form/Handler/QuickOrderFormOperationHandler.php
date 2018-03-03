<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\Handler;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToCartClientInterface;
use SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig;

class QuickOrderFormOperationHandler implements QuickOrderFormOperationHandlerInterface
{
    /**
     * @var \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig
     */
    protected $config;

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig $config
     * @param \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToCartClientInterface $cartClient
     */
    public function __construct(QuickOrderPageConfig $config, QuickOrderPageToCartClientInterface $cartClient)
    {
        $this->cartClient = $cartClient;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrder
     *
     * @return void
     */
    public function addToCart(QuickOrderTransfer $quickOrder): void
    {
        $itemTransfers = $this->getItemTransfers($quickOrder);

        if ($itemTransfers) {
            $this->addItemsToCart($itemTransfers);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrder
     *
     * @return void
     */
    public function createOrder(QuickOrderTransfer $quickOrder): void
    {
        $itemTransfers = $this->getItemTransfers($quickOrder);

        if ($itemTransfers) {
            $this->cartClient->clearQuote();
            $this->addItemsToCart($itemTransfers);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return void
     */
    protected function addItemsToCart(array $itemTransfers): void
    {
        $quoteTransfer = $this->cartClient->addItems($itemTransfers);

        $this->cartClient->storeQuote($quoteTransfer);
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
                $itemTransfer = (new ItemTransfer())
                    ->setSku($quickOrderItemTransfer->getSku())
                    ->setQuantity($quickOrderItemTransfer->getQty());

                $itemTransfers[] = $itemTransfer;
            }
        }

        return $itemTransfers;
    }
}
