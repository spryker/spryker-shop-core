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
    /**
     * @uses \SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider::ROUTE_CART
     */
    protected const ROUTE_SUCCESSFUL_REDIRECT = 'cart';

    protected const PARAM_ITEMS = 'items';
    protected const PARAM_ID_ORDER = 'id';

    /**
     * @param int $idSalesOrder
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function reorderAction(int $idSalesOrder): RedirectResponse
    {
        $orderTransfer = $this->getFactory()
            ->createOrderReader()
            ->getOrderTransfer($idSalesOrder);

        $this->getFactory()
            ->createCartFiller()
            ->fillFromOrder($orderTransfer);

        $this->getFactory()
            ->getZedRequestClient()
            ->addFlashMessagesFromLastZedRequest();

        return $this->getSuccessRedirect();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function reorderItemsAction(Request $request): RedirectResponse
    {
        $idSalesOrder = $request->request->getInt(static::PARAM_ID_ORDER);
        $items = (array)$request->request->get(static::PARAM_ITEMS);

        $orderTransfer = $this->getFactory()
            ->createOrderReader()
            ->getOrderTransfer($idSalesOrder);

        $this->getFactory()
            ->createCartFiller()
            ->fillSelectedFromOrder($orderTransfer, $items);

        $this->getFactory()
            ->getZedRequestClient()
            ->addFlashMessagesFromLastZedRequest();

        return $this->getSuccessRedirect();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function getSuccessRedirect(): RedirectResponse
    {
        return $this->redirectResponseInternal(static::ROUTE_SUCCESSFUL_REDIRECT);
    }
}
