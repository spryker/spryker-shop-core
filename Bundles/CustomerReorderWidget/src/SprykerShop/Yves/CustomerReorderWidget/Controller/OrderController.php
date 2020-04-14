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
    protected const GLOSSARY_KEY_ERROR_MESSAGE_UNABLE_TO_REORDER_ITEMS = 'customer.order.reorder.error.unable_to_reorder_items';

    /**
     * @uses \SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider::ROUTE_CART
     */
    protected const ROUTE_SUCCESSFUL_REDIRECT = 'cart';
    protected const ROUTE_FAILURE_REDIRECT = 'customer/order';

    protected const PARAM_ITEMS = 'items';
    protected const PARAM_ID_ORDER = 'id';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $idSalesOrder
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function reorderAction(Request $request, int $idSalesOrder): RedirectResponse
    {
        $form = $this->getFactory()->createCustomerReorderWidgetFormFactory()
            ->getCustomerReorderWidgetForm()->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->getFailureRedirect();
        }

        $orderReader = $this->getFactory()
            ->createOrderReader();

        $orderTransfer = $orderReader->getOrderTransfer($idSalesOrder);
        if ($orderReader->hasIncompatibleItems($orderTransfer)) {
            $this->getFactory()
                ->getMessengerClient()
                ->addErrorMessage(static::GLOSSARY_KEY_ERROR_MESSAGE_UNABLE_TO_REORDER_ITEMS);

            return $this->getFailureRedirect();
        }

        $this->getFactory()
            ->createCartFiller()
            ->fillFromOrder($orderTransfer);

        $this->getFactory()
            ->getZedRequestClient()
            ->addResponseMessagesToMessenger();

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

        $orderReader = $this->getFactory()
            ->createOrderReader();

        $orderTransfer = $orderReader->getOrderTransfer($idSalesOrder);
        if ($orderReader->hasIncompatibleItems($orderTransfer)) {
            $this->getFactory()
                ->getMessengerClient()
                ->addErrorMessage(static::GLOSSARY_KEY_ERROR_MESSAGE_UNABLE_TO_REORDER_ITEMS);

            return $this->getFailureRedirect();
        }

        $this->getFactory()
            ->createCartFiller()
            ->fillSelectedFromOrder($orderTransfer, $items);

        $this->getFactory()
            ->getZedRequestClient()
            ->addResponseMessagesToMessenger();

        return $this->getSuccessRedirect();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function getSuccessRedirect(): RedirectResponse
    {
        return $this->redirectResponseInternal(static::ROUTE_SUCCESSFUL_REDIRECT);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function getFailureRedirect(): RedirectResponse
    {
        return $this->redirectResponseInternal(static::ROUTE_FAILURE_REDIRECT);
    }
}
