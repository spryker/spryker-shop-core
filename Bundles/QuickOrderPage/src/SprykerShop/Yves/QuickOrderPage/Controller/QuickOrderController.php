<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Controller;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Generated\Shared\Transfer\SpyProductAbstractEntityTransfer;
use SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider;
use SprykerShop\Yves\CheckoutPage\Plugin\Provider\CheckoutPageControllerProvider;
use SprykerShop\Yves\QuickOrderPage\Form\QuickOrder;
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

    public const ROWS_NUMBER = 10;

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
                $this->addToCart($quickOrder);

                return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
            }

            if ($request->get(QuickOrderForm::SUBMIT_BUTTON_CREATE_ORDER) !== null) {
                $this->createOrder($quickOrder);

                return $this->redirectResponseInternal(CheckoutPageControllerProvider::CHECKOUT_INDEX);
            }
        }

        $data = [
            'form' => $quickOrderForm->createView(),
            'rowsNumber' => static::ROWS_NUMBER,
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

    protected function expandSearchResults(array $searchResults): array
    {
        $productAbstracts = $searchResults['suggestionByType']['product_abstract'] ?? [];

        $searchResults = [];
        foreach ($productAbstracts as $productAbstract) {
            $productAbstractFromStorage = $this->getFactory()
                ->getProductStorageClient()
                ->getProductAbstractStorageData($productAbstract['id_product_abstract'], $this->getLocale());

            $idProductConcreteCollection = $productAbstractFromStorage['attribute_map']['product_concrete_ids'] ?? [];

            foreach ($idProductConcreteCollection as $sku => $idProductConcrete) {
                $price = $productAbstract['price'];

                $priceProductStorageTransfer = $this->getFactory()
                    ->getPriceProductStorageClient()
                    ->getProductConcretePrice($idProductConcrete);

                if ($priceProductStorageTransfer) {
                    $priceMap = $priceProductStorageTransfer->getPrices();
                    $priceTransfer = $this->getFactory()->getPriceProductClient()->resolveProductPrice($priceMap);
                    $price = $priceTransfer->getPrice();
                }

                $searchResults[] = [
                    'value' => $sku . ' - ' . $productAbstract['abstract_name'],
                    'data' => [
                        'abstractSku' => $productAbstract['abstract_sku'],
                        'sku' => $sku,
                        'price' => $price,
                    ]
                ];
            }
        }

        return  $searchResults;
    }

    //****** ********* *********  Move to operation separate class ********* *************/

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\Form\QuickOrder $quickOrder
     *
     * @return void
     */
    protected function addToCart(QuickOrder $quickOrder): void
    {
        $itemTransfers = $this->getItemTransfers($quickOrder);

        if ($itemTransfers) {
            $this->addItemsToCart($itemTransfers);
        }
    }

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\Form\QuickOrder $quickOrder
     *
     * @return void
     */
    protected function createOrder(QuickOrder $quickOrder): void
    {
        $itemTransfers = $this->getItemTransfers($quickOrder);

        if ($itemTransfers) {
            $this->getFactory()
                ->getCartClient()
                ->clearQuote();

            $this->addItemsToCart($itemTransfers);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return void
     */
    protected function addItemsToCart(array $itemTransfers): void
    {
        $quoteTransfer = $this->getFactory()
            ->getCartClient()
            ->addItems($itemTransfers);

        $this->getFactory()
            ->getCartClient()
            ->storeQuote($quoteTransfer);
    }

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\Form\QuickOrder $quickOrder
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getItemTransfers(QuickOrder $quickOrder): array
    {
        $itemTransfers = [];
        $quickOrderItemTransfers = $quickOrder->getItems();

        foreach ($quickOrderItemTransfers as $quickOrderItemTransfer) {
            if ($quickOrderItemTransfer->getSku() && $quickOrderItemTransfer->getQty()) {
                $itemTransfer = (new ItemTransfer())
                    ->setSku($quickOrderItemTransfer->getSku())
                    ->setQuantity($quickOrderItemTransfer->getQty());

                $itemTransfers[] = $itemTransfer;
            }
        }

        return $itemTransfers;
    }

    //******* ********* ********* end moving ********* ************* *************/

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Form\QuickOrder
     */
    protected function getQuickOrder(): QuickOrder
    {
        $quickOrder = new QuickOrder();
        $orderItems = [];
        for ($i = 0; $i < static::ROWS_NUMBER; $i++) {
            $orderItems[] = new QuickOrderItemTransfer();
        }
        $quickOrder->setItems($orderItems);

        return $quickOrder;
    }
}
