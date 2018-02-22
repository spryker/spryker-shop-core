<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Controller;



use Generated\Shared\Transfer\QuickOrderItemTransfer;

use SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider;
use SprykerShop\Yves\CheckoutPage\Plugin\Provider\CheckoutPageControllerProvider;
use SprykerShop\Yves\QuickOrderPage\Form\QuickOrderData;
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

    public const PRODUCT_ROWS_NUMBER = 10;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        $quickOrder = $this->getQuickOrder();

        $quickOrderForm = $this
            ->getFactory()
            ->createQuickOrderFormFactory()
            ->getQuickOrderForm($quickOrder)
            ->handleRequest($request);

        if ($quickOrderForm->isSubmitted() && $quickOrderForm->isValid()) {
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

            if ($request->get(QuickOrderForm::SUBMIT_BUTTON_VERIFY) !== null) {
                $this->getFactory()
                    ->createFormOperationHandler()
                    ->verifyTextOrder($quickOrder, $quickOrderForm);
            }
        }

        $data = [
            'form' => $quickOrderForm->createView(),
            'rowsNumber' => static::PRODUCT_ROWS_NUMBER,
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
        $searchString = $request->query->get(self::PARAM_SEARCH_QUERY);

        if (empty($searchString)) {
            return $this->jsonResponse(['suggestions' => []]);
        }

        $searchResults = $this
            ->getFactory()
            ->getCatalogClient()
            ->catalogSuggestSearch($searchString, $request->query->all());

        $searchResults = $this->expandSearchResults($searchResults);

        return $this->jsonResponse(['suggestions' => $searchResults]);
    }

    /**
     * @param array $searchResults
     * *
     * @return array
     */
    protected function expandSearchResults(array $searchResults): array
    {
        $productAbstracts = $searchResults['suggestionByType']['product_abstract'] ?? [];

        $searchResults = [];
        foreach ($productAbstracts as $productAbstract) {
            $data = $this->getFactory()
                ->getProductStorageClient()
                ->getProductAbstractStorageData($productAbstract['id_product_abstract'], $this->getLocale());

            $idProductConcreteCollection = $data['attribute_map']['product_concrete_ids'] ?? [];

            foreach ($idProductConcreteCollection as $sku => $idProductConcrete) {
                $data['id_product_concrete'] = $idProductConcrete;
                $data['sku'] = $sku;

                $productViewTransfer = $this->getFactory()
                    ->getProductStorageClient()
                    ->mapProductStorageData($data, $this->getLocale());

                $searchResults[] = [
                    'value' => $productViewTransfer->getSku() . ' - ' . $productViewTransfer->getName(),
                    'data' => [
                        'abstractSku' => $productAbstract['abstract_sku'],
                        'sku' => $productViewTransfer->getSku(),
                        'price' => $productViewTransfer->getPrice(),
                        'available' => $productViewTransfer->getAvailable()
                    ]
                ];
            }
        }

        return  $searchResults;
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Form\QuickOrderData
     */
    protected function getQuickOrder(): QuickOrderData
    {
        $quickOrder = new QuickOrderData();
        $orderItems = [];
        for ($i = 0; $i < static::PRODUCT_ROWS_NUMBER; $i++) {
            $orderItems[] = new QuickOrderItemTransfer();
        }
        $quickOrder->setItems($orderItems);

        return $quickOrder;
    }
}
