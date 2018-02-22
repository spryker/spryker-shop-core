<?php
namespace SprykerShop\Yves\QuickOrderPage\Handler;

use SprykerShop\Yves\QuickOrderPage\Form\QuickOrderData;
use Symfony\Component\Form\Form;

interface QuickOrderFormOperationHandlerInterface
{
    /**
     * @param \SprykerShop\Yves\QuickOrderPage\Form\QuickOrderData $quickOrder
     *
     * @return void
     */
    public function addToCart(QuickOrderData $quickOrder): void;

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\Form\QuickOrderData $quickOrder
     *
     * @return void
     */
    public function createOrder(QuickOrderData $quickOrder): void;

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\Form\QuickOrderData $quickOrder
     * @param \Symfony\Component\Form\Form $quickOrderForm
     */
    public function verifyTextOrder(QuickOrderData $quickOrder, Form $quickOrderForm): void;
}
