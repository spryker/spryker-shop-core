<?php

namespace SprykerShop\Yves\QuickOrderPage\Handler;

use Generated\Shared\Transfer\ItemTransfer;
use SprykerShop\Yves\QuickOrderPage\Form\QuickOrderData;
use SprykerShop\Yves\QuickOrderPage\Form\QuickOrderForm;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToCartClientInterface;
use Symfony\Component\Form\Form;

class QuickOrderFormOperationHandler implements QuickOrderFormOperationHandlerInterface
{
    /**
     * @var \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToCartClientInterface
     */
    protected $cartClient;

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToCartClientInterface $cartClient
     */
    public function __construct(QuickOrderPageToCartClientInterface $cartClient)
    {
        $this->cartClient = $cartClient;
    }

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\Form\QuickOrderData $quickOrder
     *
     * @return void
     */
    public function addToCart(QuickOrderData $quickOrder): void
    {
        $itemTransfers = $this->getItemTransfers($quickOrder);

        if ($itemTransfers) {
            $this->addItemsToCart($itemTransfers);
        }
    }

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\Form\QuickOrderData $quickOrder
     *
     * @return void
     */
    public function createOrder(QuickOrderData $quickOrder): void
    {
        $itemTransfers = $this->getItemTransfers($quickOrder);

        if ($itemTransfers) {
            $this->cartClient->clearQuote();
            $this->addItemsToCart($itemTransfers);
        }
    }

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\Form\QuickOrderData $quickOrder
     * @param \Symfony\Component\Form\Form $quickOrderForm
     */
    public function verifyTextOrder(QuickOrderData $quickOrder, Form $quickOrderForm): void
    {
        die($quickOrder->getTextOrder());
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
     * @param \SprykerShop\Yves\QuickOrderPage\Form\QuickOrderData $quickOrder
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getItemTransfers(QuickOrderData $quickOrder): array
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
