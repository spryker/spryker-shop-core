<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Handler;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
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
     * @var \SprykerShop\Yves\CustomerReorderWidget\Handler\QuoteWriterInterface
     */
    private $quoteWriter;

    /**
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface $cartClient
     * @param \SprykerShop\Yves\CustomerReorderWidget\Handler\ItemsFetcherInterface $itemsFetcher
     * @param \SprykerShop\Yves\CustomerReorderWidget\Handler\QuoteWriterInterface $quoteWriter
     */
    public function __construct(
        CustomerReorderWidgetToCartClientInterface $cartClient,
        ItemsFetcherInterface $itemsFetcher,
        QuoteWriterInterface $quoteWriter
    ) {
        $this->cartClient = $cartClient;
        $this->itemsFetcher = $itemsFetcher;
        $this->quoteWriter = $quoteWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function reorder(OrderTransfer $orderTransfer): void
    {
        $items = $this->itemsFetcher->getAll($orderTransfer);
        $quoteTransfer = $this->quoteWriter->fill($orderTransfer);

        $this->updateCart($quoteTransfer, $items);
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
        $quoteTransfer = $this->quoteWriter->fill($orderTransfer);

        $this->updateCart($quoteTransfer, $items);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItems
     *
     * @return void
     */
    protected function updateCart(QuoteTransfer $quoteTransfer, array $orderItems): void
    {
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setQuote($quoteTransfer);
        $orderItemsObject = new ArrayObject($orderItems);
        $cartChangeTransfer->setItems($orderItemsObject);

        $quoteTransfer = $this->cartClient->addValidItems($cartChangeTransfer);

        $this->cartClient->storeQuote($quoteTransfer);
    }
}
