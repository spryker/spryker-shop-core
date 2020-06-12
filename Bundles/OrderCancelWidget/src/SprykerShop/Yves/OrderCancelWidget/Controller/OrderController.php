<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OrderCancelWidget\Controller;

use Generated\Shared\Transfer\OrderCancelRequestTransfer;
use Generated\Shared\Transfer\OrderCancelResponseTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\OrderCancelWidget\OrderCancelWidgetFactory getFactory()
 */
class OrderController extends AbstractController
{
    protected const PARAMETER_ORDER_REFERENCE = 'orderReference';
    protected const PARAMETER_RETURN_URL = 'returnUrl';

    protected const GLOSSARY_KEY_ORDER_CANCELLED = 'order_cancel_widget.order.cancelled';

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        if (!$this->getFactory()->getCustomerClient()->getCustomer()) {
            throw new NotFoundHttpException(
                'Only logged in customers are allowed to access this page'
            );
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function cancelAction(Request $request): RedirectResponse
    {
        $response = $this->executeCancelAction($request);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeCancelAction(Request $request): RedirectResponse
    {
        $returnUrl = $request->query->get(static::PARAMETER_RETURN_URL);
        $form = $this->getFactory()->getOrderCancelForm()->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->redirectResponseExternal($returnUrl);
        }

        $orderCancelRequestTransfer = (new OrderCancelRequestTransfer())
            ->setCustomer($this->getFactory()->getCustomerClient()->getCustomer())
            ->setOrderReference($request->get(static::PARAMETER_ORDER_REFERENCE));

        $orderCancelResponseTransfer = $this->getFactory()->getSalesClient()->cancelOrder($orderCancelRequestTransfer);

        $this->handleResponseErrors($orderCancelResponseTransfer);

        if ($orderCancelResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_ORDER_CANCELLED);
        }

        return $this->redirectResponseExternal($returnUrl);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderCancelResponseTransfer $orderCancelResponseTransfer
     *
     * @return void
     */
    protected function handleResponseErrors(OrderCancelResponseTransfer $orderCancelResponseTransfer): void
    {
        foreach ($orderCancelResponseTransfer->getMessages() as $messageTransfer) {
            $this->addErrorMessage($messageTransfer->getValue());
        }
    }
}
