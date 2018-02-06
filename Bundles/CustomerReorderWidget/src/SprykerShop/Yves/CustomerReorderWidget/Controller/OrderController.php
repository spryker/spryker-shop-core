<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Controller;

use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CustomerReorderWidget\CustomerReorderWidgetFactory getFactory()
 */
class OrderController extends AbstractController
{
    const PARAM_ITEMS = 'items';
    const ORDER_ID = 'id';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function reorderAction(Request $request)
    {
        $idSalesOrder = $request->query->getInt(self::ORDER_ID);
        $customerTransfer = $this->getLoggedInCustomerTransfer();

        $this->getFactory()
            ->createReorderHandler()
            ->reorder($idSalesOrder, $customerTransfer);

        //todo route from config
        return $this->redirectResponseInternal('checkout-index');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function reorderItemsAction(Request $request)
    {
        $idSalesOrder = $request->request->getInt(self::ORDER_ID);
        $items = (array)$request->request->get(self::PARAM_ITEMS);
        $customerTransfer = $this->getLoggedInCustomerTransfer();

        $this->getFactory()
            ->createReorderHandler()
            ->reorderItems($idSalesOrder, $customerTransfer, $items);

        //todo route from config
        return $this->redirectResponseInternal('checkout-index');
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    protected function getLoggedInCustomerTransfer()
    {
        if ($this->getFactory()->getCustomerClient()->isLoggedIn()) {
            return $this->getFactory()->getCustomerClient()->getCustomer();
        }

        return null;
    }
}
