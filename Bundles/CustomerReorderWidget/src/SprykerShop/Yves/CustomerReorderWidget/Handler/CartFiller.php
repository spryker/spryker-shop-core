<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Handler;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface;

class CartFiller implements CartFillerInteface
{
    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Handler\ItemsFetcherInterface
     */
    private $itemsFetcher;

    /**
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface $cartClient
     * @param \SprykerShop\Yves\CustomerReorderWidget\Handler\ItemsFetcherInterface $itemsFetcher
     */
    public function __construct(
        CustomerReorderWidgetToCartClientInterface $cartClient,
        ItemsFetcherInterface $itemsFetcher
    ) {
        $this->cartClient = $cartClient;
        $this->itemsFetcher = $itemsFetcher;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function reorder(OrderTransfer $orderTransfer): void
    {
        $items = $this->itemsFetcher->getAll($orderTransfer);

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
        $items = $this->itemsFetcher->getByIds($orderTransfer, $idOrderItems);

        $this->updateCart($items);
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

        foreach ($orderItems as $item) {
            $quoteTransfer = $this->cartClient->addItem($item);
            $this->cartClient->storeQuote($quoteTransfer);
        }
    }
}
