<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Controller;

use SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider;
use SprykerShop\Yves\CheckoutPage\Plugin\Provider\CheckoutPageControllerProvider;
use SprykerShop\Yves\QuickOrderPage\Form\QuickOrderForm;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $quickOrderForm = $this
            ->getFactory()
            ->createQuickOrderFormFactory()
            ->getQuickOrderForm()
            ->handleRequest($request);

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

        $data = [
            'form' => $quickOrderForm->createView(),
            'rowsNumber' => $this->getFactory()->getBundleConfig()->getProductRowsNumber(),
        ];

        return $this->view($data);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function suggestionAction(Request $request): JsonResponse
    {
        $searchString = $request->query->get(self::PARAM_SEARCH_QUERY, '');
        $searchField = $request->query->get(self::PARAM_SEARCH_FIELD, '');

        if (empty($searchString)) {
            return $this->jsonResponse([self::RESPONSE_SUGGESTION => []]);
        }

        $suggestions = $this->getSuggestionCollection($searchString, $searchField);

        return $this->jsonResponse([self::RESPONSE_SUGGESTION => $suggestions]);
    }

    /**
     * @param string $searchString
     * @param string $searchField
     *
     * @return array
     */
    protected function getSuggestionCollection(string $searchString, string $searchField): array
    {
        $suggestions = [];
        $limit = $this->getFactory()->getBundleConfig()->getSuggestionResultsLimit();

        $productViewTransfers = $this->getFactory()
            ->createProductFinder()
            ->getSearchResults($searchString, $searchField, $limit);

        foreach ($productViewTransfers as $productViewTransfer) {
            $suggestions[] = [
                'value' => $productViewTransfer->getSku() . ' - ' . $productViewTransfer->getName(),
                'data' => [
                    'idAbstractProduct' => $productViewTransfer->getIdProductAbstract(),
                    'sku' => $productViewTransfer->getSku(),
                    'price' => $productViewTransfer->getPrice(),
                    'available' => $productViewTransfer->getAvailable(),
                ],
            ];
        }

        return $suggestions;
    }
}
