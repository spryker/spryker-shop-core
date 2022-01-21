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
class OrderCancelController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAMETER_RETURN_URL = 'return-url';

    /**
     * @var string
     */
    protected const PARAMETER_ID_SALES_ORDER = 'id-sales-order';

    /**
     * @var string
     */
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
                'Only logged in customers are allowed to access this page',
            );
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        $response = $this->executeIndexAction($request);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeIndexAction(Request $request): RedirectResponse
    {
        $returnUrl = (string)$request->query->get(static::PARAMETER_RETURN_URL);
        $form = $this->getFactory()->getOrderCancelForm()->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->redirectResponseExternal($returnUrl);
        }

        $orderCancelRequestTransfer = (new OrderCancelRequestTransfer())
            ->setCustomer($this->getFactory()->getCustomerClient()->getCustomer())
            ->setIdSalesOrder($request->get(static::PARAMETER_ID_SALES_ORDER));

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
