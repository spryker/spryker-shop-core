<?php
/**
 * Created by PhpStorm.
 * User: matveyev
 * Date: 2/15/18
 * Time: 14:15
 */

namespace SprykerShop\Yves\QuickOrderPage\Controller;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use SprykerShop\Yves\CartPage\CartPageDependencyProvider;
use SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider;
use SprykerShop\Yves\QuickOrderPage\Form\QuickOrder;
use SprykerShop\Yves\QuickOrderPage\Form\QuickOrderForm;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\QuickOrderPage\QuickOrderPageFactory getFactory()
 */
class QuickOrderController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        $quickOrder = $this->getQuickOrder();

        $quickOrderForm = $this
            ->getFactory()
            ->createQuickOrderFormFactory()
            ->getQuickOrderForm($quickOrder)
            ->handleRequest($request);

        if ($quickOrderForm->isSubmitted() && $quickOrderForm->isValid()) {
            if ($request->get(QuickOrderForm::SUBMIT_BUTTON_ADD_TO_CART) !== null) {
                $this->addToCart($quickOrder->getItems());

                return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
            }

            if ($request->get(QuickOrderForm::SUBMIT_BUTTON_CREATE_ORDER) !== null) {
                $this->createOrder($quickOrder->getItems());
            }
        }

        $data = [
            'form' => $quickOrderForm->createView()
        ];

        return $this->view($data);
    }

    /**
     * @param QuickOrderItemTransfer[] $quickOrderItemTransfers
     */
    protected function addToCart(array $quickOrderItemTransfers): void
    {
        $itemTransfers = [];

        foreach ($quickOrderItemTransfers as $quickOrderItemTransfer) {
            if ($quickOrderItemTransfer->getSku() && $quickOrderItemTransfer->getQty()) {
                $itemTransfer = (new ItemTransfer())
                    ->setSku($quickOrderItemTransfer->getSku())
                    ->setQuantity($quickOrderItemTransfer->getQty());

                $itemTransfers[] = $itemTransfer;
            }
        }

        if ($itemTransfers) {
            $this->getFactory()
                ->getCartClient()
                ->clearQuote();

            $quoteTransfer = $this->getFactory()
                ->getCartClient()
                ->addItems($itemTransfers);

            $this->getFactory()
                ->getCartClient()
                ->storeQuote($quoteTransfer);
        }
    }

    /**
     * @param QuickOrderItemTransfer[] $items
     */
    protected function createOrder(array $items): void
    {
        die('Create order');
    }

    /**
     * @return QuickOrder
     */
    protected function getQuickOrder(): QuickOrder
    {
        $quickOrder = new QuickOrder();
        $orderItems = [];
        for ($i = 0; $i < 10; $i++) {
            $orderItems[] = new QuickOrderItemTransfer();
        }
        $quickOrder->setItems($orderItems);

        return $quickOrder;
    }
}
