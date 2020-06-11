<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OrderCancelWidget\Controller;

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
    protected const ROUTE_CUSTOMER_ORDER = 'customer/order';

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    public function initialize()
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
        $form = $this->getFactory()
            ->getOrderCancelForm()
            ->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            dd($request->get('orderReference'));
        }

        return $this->redirectResponseInternal(static::ROUTE_CUSTOMER_ORDER);
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
