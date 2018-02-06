<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CustomerReorderWidget\CustomerReorderWidgetFactory getFactory()
 */
class OrderController extends AbstractController
{
    const PARAM_ITEMS = 'items';
    const ORDER_ID = 'id';
    /**
     * Route for cart page.
     * Described in CartPage module
     * @see \SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider::ROUTE_CART
     */
    const ROUTE_CART = 'cart';

    /**
     * @param int $idOrder
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function reorderAction(int $idOrder): RedirectResponse
    {
        $customerTransfer = $this->getLoggedInCustomerTransfer();
        if (!$customerTransfer) {
            return $this->redirectResponseInternal('error/404');
        }

        $order = $this->getFactory()
            ->createOrderHandler()
            ->getOrderTransfer($idOrder, $customerTransfer);

        $this->getFactory()
            ->createReorderHandler()
            ->reorder($order);

        return $this->gerRedirectToCart();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function reorderItemsAction(Request $request): RedirectResponse
    {
        $customerTransfer = $this->getLoggedInCustomerTransfer();
        if (!$customerTransfer) {
            return $this->redirectResponseInternal('error/404');
        }

        $idSalesOrder = $request->request->getInt(self::ORDER_ID);
        $items = (array)$request->request->get(self::PARAM_ITEMS);

        $order = $this->getFactory()
            ->createOrderHandler()
            ->getOrderTransfer($idSalesOrder, $customerTransfer);

        $this->getFactory()
            ->createReorderHandler()
            ->reorderItems($order, $items);

        return $this->gerRedirectToCart();
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    protected function getLoggedInCustomerTransfer(): ?CustomerTransfer
    {
        if ($this->getFactory()->getCustomerClient()->isLoggedIn()) {
            return $this->getFactory()->getCustomerClient()->getCustomer();
        }

        return null;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function gerRedirectToCart(): RedirectResponse
    {
        return $this->redirectResponseInternal(static::ROUTE_CART);
    }
}
