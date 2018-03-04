<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Controller;

use ArrayObject;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;
use SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider;
use SprykerShop\Yves\CheckoutPage\Plugin\Provider\CheckoutPageControllerProvider;
use SprykerShop\Yves\QuickOrderPage\Form\QuickOrderForm;
use SprykerShop\Yves\QuickOrderPage\Form\TextOrderForm;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\QuickOrderPage\QuickOrderPageFactory getFactory()
 */
class QuickOrderController extends AbstractController
{
    public const PARAM_SEARCH_QUERY = 'q';
    public const PARAM_SEARCH_FIELD = 'field';

    public const RESPONSE_SUGGESTION = 'suggestions';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        $textOrderForm = $this
            ->getFactory()
            ->createQuickOrderFormFactory()
            ->getTextOrderForm()
            ->handleRequest($request);

        $textOrderParsedItems = $this->handleTextOrderForm($textOrderForm);
        $quickOrder = $this->getQuickOrder($textOrderParsedItems);

        $quickOrderForm = $this
            ->getFactory()
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
            'rowsNumber' => $this->getFactory()->getBundleConfig()->getProductRowsNumber(),
        ];

        return $this->view($data);
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

            return $this->getFactory()
                ->createTextOrderParser($data[TextOrderForm::FIELD_TEXT_ORDER])
                ->getOrderItems();
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
                $this->getFactory()
                    ->createFormOperationHandler()
                    ->addToCart($quickOrder);

                return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
            }

            if ($request->get(QuickOrderForm::SUBMIT_BUTTON_CREATE_ORDER) !== null) {
                $this->getFactory()
                    ->createFormOperationHandler()
                    ->createOrder($quickOrder);

                return $this->redirectResponseInternal(CheckoutPageControllerProvider::CHECKOUT_INDEX);
            }
        }

        return null;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function suggestionAction(Request $request): JsonResponse
    {
        $searchString = $request->query->get(static::PARAM_SEARCH_QUERY, '');
        $searchField = $request->query->get(static::PARAM_SEARCH_FIELD, '');

        if (empty($searchString)) {
            return $this->jsonResponse([static::RESPONSE_SUGGESTION => []]);
        }

        $suggestions = $this->getFactory()
            ->createSuggestionDataProvider()
            ->getSuggestionCollection($searchString, $searchField);

        return $this->jsonResponse([static::RESPONSE_SUGGESTION => $suggestions]);
    }

    /**
     * @param array $orderItems
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    protected function getQuickOrder(array $orderItems = []): QuickOrderTransfer
    {
        $quickOrder = new QuickOrderTransfer();
        $orderItemCollection = new ArrayObject($orderItems);
        if ($orderItemCollection->count() === 0) {
            $productRowsNumber = $this->getFactory()->getBundleConfig()->getProductRowsNumber();
            for ($i = 0; $i < $productRowsNumber; $i++) {
                $orderItemCollection->append(new QuickOrderItemTransfer());
            }
        }

        $quickOrder->setItems($orderItemCollection);

        return $quickOrder;
    }
}
