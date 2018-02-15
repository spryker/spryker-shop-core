<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Controller;

use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CustomerReorderWidget\CustomerReorderWidgetFactory getFactory()
 */
class OrderController extends AbstractController
{
    public const PARAM_ITEMS = 'items';
    public const ID_ORDER = 'id';
    /**
     * Route for cart page.
     * Described in CartPage module
     * @see \SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider::ROUTE_CART
     */
    public const ROUTE_CART = 'cart';

    /**
     * @param int $idOrder
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function reorderAction(int $idOrder): RedirectResponse
    {
        $orderTransfer = $this->getFactory()
            ->createOrderRepository()
            ->getOrderTransfer($idOrder);

        $this->getFactory()
            ->createQuoteWriter()
            ->fill($orderTransfer);

        $this->getFactory()
            ->createCartFiller()
            ->reorder($orderTransfer);

        $this->getFactory()
            ->createMessenger()
            ->setFlashMessagesFromLastZedRequest();

        return $this->gerRedirectToCart();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function reorderItemsAction(Request $request): RedirectResponse
    {
        $idSalesOrder = $request->request->getInt(static::ID_ORDER);
        $items = (array)$request->request->get(static::PARAM_ITEMS);

        $orderTransfer = $this->getFactory()
            ->createOrderRepository()
            ->getOrderTransfer($idSalesOrder);

        $this->getFactory()
            ->createQuoteWriter()
            ->fill($orderTransfer);

        $this->getFactory()
            ->createCartFiller()
            ->reorderItems($orderTransfer, $items);

        $this->getFactory()
            ->createMessenger()
            ->setFlashMessagesFromLastZedRequest();

        return $this->gerRedirectToCart();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function gerRedirectToCart(): RedirectResponse
    {
        return $this->redirectResponseInternal(static::ROUTE_CART);
    }
}
