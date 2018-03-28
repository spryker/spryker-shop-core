<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Controller;

use Generated\Shared\Transfer\QuickOrderTransfer;
use SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider;
use SprykerShop\Yves\CheckoutPage\Plugin\Provider\CheckoutPageControllerProvider;
use SprykerShop\Yves\QuickOrderPage\Form\QuickOrderForm;
use SprykerShop\Yves\QuickOrderPage\Form\TextOrderForm;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @method \SprykerShop\Yves\QuickOrderPage\QuickOrderPageFactory getFactory()
 */
class QuickOrderController extends AbstractController
{
    public const PARAM_ROW_INDEX = 'row-index';
    public const PARAM_QUICK_ORDER_FORM = 'quick_order_form';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        $textOrderForm = $this->getFactory()
            ->createQuickOrderFormFactory()
            ->getTextOrderForm()
            ->handleRequest($request);

        $textOrderParsedItems = $this->handleTextOrderForm($textOrderForm);
        $quickOrder = $this->getQuickOrderTransfer($textOrderParsedItems);

        $quickOrderForm = $this->getFactory()
            ->createQuickOrderFormFactory()
            ->getQuickOrderForm($quickOrder)
            ->handleRequest($request);

        $response = $this->handleQuickOrderForm($quickOrderForm, $request);
        if ($response !== null) {
            return $response;
        }

        $data = [
            'itemsForm' => $quickOrderForm->createView(),
            'textOrderForm' => $textOrderForm->createView(),
        ];

        return $this->view($data, [], '@QuickOrderPage/views/quick-order/quick-order.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function addRowsAction(Request $request)
    {
        if (!$request->isMethod('post')) {
            return $this->jsonResponse();
        }

        $formData = $request->get(static::PARAM_QUICK_ORDER_FORM);
        $formDataItems = $formData['items'] ?? [];

        $dataProvider = $this->getFactory()
            ->createQuickOrderFormDataProvider();

        $orderItems = $dataProvider->getOrderItemsFromFormData($formDataItems);
        $quickOrder = $this->getQuickOrderTransfer($orderItems);

        $emptyProductsNumber = $this->getFactory()
            ->getBundleConfig()
            ->getProductRowsNumber();

        $dataProvider->appendEmptyOrderItems($quickOrder, $emptyProductsNumber);

        $quickOrderForm = $this->getFactory()
            ->createQuickOrderFormFactory()
            ->getQuickOrderForm($quickOrder);

        return $this->view(['form' => $quickOrderForm->createView()], [], '@QuickOrderPage/views/quick-order-async-render/quick-order-async-render.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return mixed
     */
    public function deleteRowAction(Request $request)
    {
        if (!$request->isMethod('post')) {
            return $this->jsonResponse();
        }

        $rowIndex = $request->get(static::PARAM_ROW_INDEX);
        $formData = $request->get(static::PARAM_QUICK_ORDER_FORM);
        $formDataItems = $formData['items'] ?? [];

        if (!isset($formDataItems[$rowIndex])) {
            throw new HttpException(400, '"row-index" is out of the bound.');
        }
        unset($formDataItems[$rowIndex]);

        $orderItems = $this->getFactory()
            ->createQuickOrderFormDataProvider()
            ->getOrderItemsFromFormData($formDataItems);

        $quickOrder = $this->getQuickOrderTransfer($orderItems);

        $quickOrderForm = $this->getFactory()
            ->createQuickOrderFormFactory()
            ->getQuickOrderForm($quickOrder);

        return $this->view(['form' => $quickOrderForm->createView()], [], '@QuickOrderPage/views/quick-order-async-render/quick-order-async-render.twig');
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $textOrderForm
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer[]
     */
    protected function handleTextOrderForm(FormInterface $textOrderForm): array
    {
        if ($textOrderForm->isSubmitted() && $textOrderForm->isValid()) {
            $data = $textOrderForm->getData();

            $textOrderParser = $this->getFactory()
                ->createTextOrderParser($data[TextOrderForm::FIELD_TEXT_ORDER])
                ->parse();

            return $textOrderParser->getParsedTextOrderItems();
        }

        return [];
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $quickOrderForm
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return null|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleQuickOrderForm(FormInterface $quickOrderForm, Request $request): ?RedirectResponse
    {
        if ($quickOrderForm->isSubmitted() && $quickOrderForm->isValid()) {
            $quickOrder = $quickOrderForm->getData();

            if ($request->get(QuickOrderForm::SUBMIT_BUTTON_ADD_TO_CART) !== null) {
                $result = $this->getFactory()
                    ->createFormOperationHandler()
                    ->addToCart($quickOrder);

                if (!$result) {
                    return null;
                }

                return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
            }

            if ($request->get(QuickOrderForm::SUBMIT_BUTTON_CREATE_ORDER) !== null) {
                $result = $this->getFactory()
                    ->createFormOperationHandler()
                    ->createOrder($quickOrder);

                if (!$result) {
                    return null;
                }

                return $this->redirectResponseInternal(CheckoutPageControllerProvider::CHECKOUT_INDEX);
            }
        }

        return null;
    }

    /**
     * @param array $orderItems
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    protected function getQuickOrderTransfer(array $orderItems = []): QuickOrderTransfer
    {
        $dataProvider = $this->getFactory()
            ->createQuickOrderFormDataProvider();

        $quickOrderTransfer = $dataProvider->getQuickOrderTransfer($orderItems);
        if ($quickOrderTransfer->getItems()->count() === 0) {
            $emptyProductsNumber = $this->getFactory()
                ->getBundleConfig()
                ->getProductRowsNumber();

            $dataProvider->appendEmptyOrderItems($quickOrderTransfer, $emptyProductsNumber);
        }

        return $quickOrderTransfer;
    }
}
