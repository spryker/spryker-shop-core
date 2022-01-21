<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Controller;

use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CustomerReorderWidget\CustomerReorderWidgetFactory getFactory()
 */
class OrderController extends AbstractController
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_MESSAGE_UNABLE_TO_REORDER_ITEMS = 'customer.order.reorder.error.unable_to_reorder_items';

    /**
     * @uses \SprykerShop\Yves\CartPage\Plugin\Router\CartPageRouteProviderPlugin::ROUTE_NAME_CART
     *
     * @var string
     */
    protected const ROUTE_SUCCESSFUL_REDIRECT = 'cart';

    /**
     * @var string
     */
    protected const ROUTE_FAILURE_REDIRECT = 'customer/order';

    /**
     * @var string
     */
    protected const PARAM_ITEMS = 'items';

    /**
     * @var string
     */
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
            $this->addErrorMessagesFromForm($form);

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
        $form = $this->getFactory()->createCustomerReorderWidgetFormFactory()
            ->getCustomerReorderItemsWidgetForm()->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorMessagesFromForm($form);

            return $this->getFailureRedirect();
        }

        $idSalesOrder = $request->request->getInt(static::PARAM_ID_ORDER);
        /** @var array<int> $items */
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

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return void
     */
    protected function addErrorMessagesFromForm(FormInterface $form): void
    {
        /** @var array<\Symfony\Component\Form\FormError> $errors */
        $errors = $form->getErrors(true);
        foreach ($errors as $error) {
            $this->addErrorMessage($error->getMessage());
        }
    }
}
