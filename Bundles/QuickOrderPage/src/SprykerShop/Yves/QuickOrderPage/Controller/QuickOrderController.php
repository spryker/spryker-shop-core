<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Controller;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
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
    public const PARAM_ID_PRODUCT = 'id-product';
    public const PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';
    public const PARAM_QUANTITY = 'quantity';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        $response = $this->executeIndexAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view(
            $response,
            $this->getFactory()->getQuickOrderPageWidgetPlugins(),
            '@QuickOrderPage/views/quick-order/quick-order.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeIndexAction(Request $request)
    {
        $textOrderForm = $this->getFactory()
            ->createQuickOrderFormFactory()
            ->getTextOrderForm()
            ->handleRequest($request);

        $quickOrderTransfer = $this->handleTextOrderForm($textOrderForm);
        $quickOrderForm = $this->getFactory()
            ->createQuickOrderFormFactory()
            ->getQuickOrderForm($quickOrderTransfer)
            ->handleRequest($request);

        if ($quickOrderForm->isSubmitted() && $quickOrderForm->isValid()) {
            $response = $this->handleQuickOrderForm($quickOrderForm, $request);

            if ($response !== null) {
                return $response;
            }
        }

        /** @var \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer */
        $quickOrderTransfer = $quickOrderForm->getData();
        $products = $this->getProductsForQuickOrderTransfer($quickOrderTransfer);

        $quickOrderTransfer = $this->applyQuickOrderItemFilterPluginsOnQuickOrder($quickOrderTransfer, $products);
        $quickOrderTransfer = $this->setSumPriceForQuickOrderTransfer($quickOrderTransfer);

        $quickOrderForm = $this->getFactory()
            ->createQuickOrderFormFactory()
            ->getQuickOrderForm($quickOrderTransfer);

        return [
            'quickOrderForm' => $quickOrderForm->createView(),
            'textOrderForm' => $textOrderForm->createView(),
            'additionalColumns' => $this->mapAdditionalQuickOrderFormColumnPluginsToArray(),
            'products' => $products,
        ];
    }

    /**
     * @param QuickOrderTransfer $quickOrderTransfer
     * @param ProductConcreteTransfer[] $products
     *
     * @return QuickOrderTransfer
     */
    protected function applyQuickOrderItemFilterPluginsOnQuickOrder(QuickOrderTransfer $quickOrderTransfer, array $products): QuickOrderTransfer
    {
        $quickOrderItems = [];
        foreach ($quickOrderTransfer->getItems() as $quickOrderItemTransfer) {
            $quickOrderItems[] = $this->applyQuickOrderItemFilterPluginsOnQuickOrderItem($quickOrderItemTransfer, $products[$quickOrderItemTransfer->getSku()] ?? null);
        }
        $quickOrderTransfer->setItems(new ArrayObject($quickOrderItems));

        return $quickOrderTransfer;
    }

    /**
     * @param QuickOrderItemTransfer $quickOrderItemTransfer
     * @param ProductConcreteTransfer $product
     *
     * @return QuickOrderItemTransfer
     */
    protected function applyQuickOrderItemFilterPluginsOnQuickOrderItem(QuickOrderItemTransfer $quickOrderItemTransfer, ?ProductConcreteTransfer $product): QuickOrderItemTransfer
    {
        if (empty($quickOrderItemTransfer->getSku())) {
            return $quickOrderItemTransfer;
        }

        foreach ($this->getFactory()->getQuickOrderItemFilterPlugins() as $quickOrderItemFilterPlugin) {
            $quickOrderItemTransfer = $quickOrderItemFilterPlugin->filterItem($quickOrderItemTransfer, $product);
        }

        return $quickOrderItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    protected function getProductsForQuickOrderTransfer(QuickOrderTransfer $quickOrderTransfer): array
    {
        $productConcreteStorageTransfers = $this->getProductConcretesStorageTransfers($quickOrderTransfer->getItems()->getArrayCopy());
        $products = [];
        foreach ($productConcreteStorageTransfers as $productConcreteStorageTransfer) {
            $product = (new ProductConcreteTransfer())
                ->setIdProductConcrete($productConcreteStorageTransfer["id_product_concrete"]);

            $products[$productConcreteStorageTransfer["sku"]] = $this->getFactory()
                ->getQuickOrderClient()
                ->expandProductConcrete($product);
        }

        return $products;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    protected function setSumPriceForQuickOrderTransfer(QuickOrderTransfer $quickOrderTransfer): QuickOrderTransfer
    {
        /** @var \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer */
        foreach ($quickOrderTransfer->getItems() as $quickOrderItemTransfer) {
            $quickOrderItemTransfer->setSumPrice(
                $this->getSumPriceForQuickOrderItem($quickOrderItemTransfer)
            );
        }

        return $quickOrderTransfer;
    }

    /**
     * @return array
     */
    protected function mapAdditionalQuickOrderFormColumnPluginsToArray()
    {
        $additionalColumns = [];
        foreach ($this->getFactory()->getQuickOrderFormColumnPlugins() as $additionalColumnPlugin) {
            $additionalColumns[] = [
                'title' => $additionalColumnPlugin->getColumnTitle(),
                'fieldName' => $additionalColumnPlugin->getFieldName(),
            ];
        }

        return $additionalColumns;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addRowsAction(Request $request)
    {
        if (!$request->isMethod('post')) {
            return $this->jsonResponse();
        }

        $viewData = $this->executeAddRowsAction($request);

        return $this->view(
            $viewData,
            $this->getFactory()->getQuickOrderPageWidgetPlugins(),
            '@QuickOrderPage/views/quick-order-rows-async/quick-order-rows-async.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeAddRowsAction(Request $request): array
    {
        $formData = $request->get(static::PARAM_QUICK_ORDER_FORM);
        $formDataItems = $formData['items'] ?? [];

        $quickOrderFormDataProvider = $this->getFactory()
            ->createQuickOrderFormDataProvider();

        $quickOrderItems = $quickOrderFormDataProvider->mapFormDataToQuickOrderItems($formDataItems);
        $quickOrderTransfer = $this->getQuickOrderTransfer($quickOrderItems);
        $quickOrderFormDataProvider->appendEmptyQuickOrderItems($quickOrderTransfer);

        $quickOrderForm = $this->getFactory()
            ->createQuickOrderFormFactory()
            ->getQuickOrderForm($quickOrderTransfer);

        return [
            'form' => $quickOrderForm->createView(),
            'additionalColumns' => $this->mapAdditionalQuickOrderFormColumnPluginsToArray(),
            'products' => $this->getProductConcretesStorageTransfers($quickOrderItems),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteRowAction(Request $request)
    {
        if (!$request->isMethod('post')) {
            return $this->jsonResponse();
        }

        $viewData = $this->executeDeleteRowAction($request);

        return $this->view(
            $viewData,
            $this->getFactory()->getQuickOrderPageWidgetPlugins(),
            '@QuickOrderPage/views/quick-order-rows-async/quick-order-rows-async.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return array
     */
    protected function executeDeleteRowAction(Request $request): array
    {
        $rowIndex = $request->get(static::PARAM_ROW_INDEX);
        $formData = $request->get(static::PARAM_QUICK_ORDER_FORM);
        $formDataItems = $formData['items'] ?? [];

        if (!isset($formDataItems[$rowIndex])) {
            throw new HttpException(400, '"row-index" is out of the bound.');
        }
        unset($formDataItems[$rowIndex]);

        $quickOrderItems = $this->getFactory()
            ->createQuickOrderFormDataProvider()
            ->mapFormDataToQuickOrderItems($formDataItems);

        $quickOrder = $this->getQuickOrderTransfer($quickOrderItems);

        $quickOrderForm = $this->getFactory()
            ->createQuickOrderFormFactory()
            ->getQuickOrderForm($quickOrder);

        return [
            'form' => $quickOrderForm->createView(),
            'additionalColumns' => $this->mapAdditionalQuickOrderFormColumnPluginsToArray(),
            'products' => $this->getProductConcretesStorageTransfers($quickOrderItems),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productAdditionalDataAction(Request $request)
    {
        $quantity = $request->get('quantity') ?: 1;
        $sku = $request->query->get('sku');

        $quickOrderItemTransfer = (new QuickOrderItemTransfer())
            ->setQuantity($quantity)
            ->setSku($sku);

        $idProduct = $this->getIdProductBySku($sku);
        $idProductAbstract = $this->getIdProductAbstractByIdProduct($idProduct);

        $productConcreteTransfer = $this->executeProductAdditionalDataAction($idProduct);
        foreach ($this->getFactory()->getQuickOrderItemFilterPlugins() as $quickOrderItemFilterPlugin) {
            $quickOrderItemTransfer = $quickOrderItemFilterPlugin->filterItem($quickOrderItemTransfer, $productConcreteTransfer);
        }
        $sumPrice = $this->getSumPriceForQuantity($quickOrderItemTransfer->getQuantity(), $idProduct, $idProductAbstract);

        $viewData = [
            'quantity' => $quickOrderItemTransfer->getQuantity(),
            'price' => $sumPrice,
            'additionalColumns' => $this->mapAdditionalQuickOrderFormColumnPluginsToArray(),
            'product' => $productConcreteTransfer,
        ];

        return $this->view(
            $viewData,
            $this->getFactory()->getQuickOrderPageWidgetPlugins(),
            '@QuickOrderPage/views/quick-order-row-async/quick-order-row-async.twig'
        );
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    protected function getIdProductBySku(string $sku): int
    {
        $productConcreteData = $this->getFactory()
            ->getProductStorageClient()
            ->findProductConcreteStorageDataByMappingForCurrentLocale('sku', $sku);

        return $productConcreteData['id_product_concrete'];
    }

    /**
     * @param int $idProduct
     *
     * @return int
     */
    protected function getIdProductAbstractByIdProduct(int $idProduct): int
    {
        $productConcreteStorageTransfers = $this->getFactory()
            ->getProductStorageClient()
            ->getProductConcreteStorageTransfers([$idProduct]);

        return $productConcreteStorageTransfers[0]->getIdProductAbstract();
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return int
     */
    protected function getSumPriceForQuickOrderItem(QuickOrderItemTransfer $quickOrderItemTransfer): int
    {
        if (empty($quickOrderItemTransfer->getSku()) || empty($quickOrderItemTransfer->getQuantity())) {
            return 0;
        }

        $idProduct = $this->getIdProductBySku($quickOrderItemTransfer->getSku());

        return $this->getSumPriceForQuantity(
            (int)$quickOrderItemTransfer->getQuantity(),
            $idProduct,
            $this->getIdProductAbstractByIdProduct($idProduct)
        );
    }

    /**
     * @param int $quantity
     * @param int $idProduct
     * @param int $idProductAbstract
     *
     * @return int
     */
    protected function getSumPriceForQuantity(int $quantity, int $idProduct, int $idProductAbstract): int
    {
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setQuantity($quantity)
            ->setIdProduct($idProduct)
            ->setIdProductAbstract($idProductAbstract);

        return $this->getFactory()
            ->getPriceProductStorageClient()
            ->getResolvedCurrentProductPriceTransfer($priceProductFilterTransfer)
            ->getSumPrice();
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function executeProductAdditionalDataAction(int $idProduct): ProductConcreteTransfer
    {
        $productConcreteTransfer = (new ProductConcreteTransfer())
            ->setIdProductConcrete($idProduct);

        $productConcreteTransfer = $this->getFactory()
            ->getQuickOrderClient()
            ->expandProductConcrete($productConcreteTransfer);

        return $productConcreteTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    protected function createPriceProductFilterTransfer(Request $request): PriceProductFilterTransfer
    {
        return (new PriceProductFilterTransfer())
            ->setQuantity($request->query->getInt(static::PARAM_QUANTITY, 0))
            ->setIdProduct($request->query->getInt(static::PARAM_ID_PRODUCT))
            ->setIdProductAbstract($request->query->getInt(static::PARAM_ID_PRODUCT_ABSTRACT));
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $textOrderForm
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    protected function handleTextOrderForm(FormInterface $textOrderForm): QuickOrderTransfer
    {
        $quickOrderItems = [];
        if ($textOrderForm->isSubmitted() && $textOrderForm->isValid()) {
            $data = $textOrderForm->getData();

            $quickOrderItems = $this->getFactory()
                ->createTextOrderParser()
                ->parse($data[TextOrderForm::FIELD_TEXT_ORDER]);
        }

        $quickOrderTransfer = $this->getQuickOrderTransfer($quickOrderItems);

        return $quickOrderTransfer;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $quickOrderForm
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|null
     */
    protected function handleQuickOrderForm(FormInterface $quickOrderForm, Request $request): ?RedirectResponse
    {
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
                ->addToEmptyCart($quickOrder);

            if (!$result) {
                return null;
            }

            return $this->redirectResponseInternal(CheckoutPageControllerProvider::CHECKOUT_INDEX);
        }

        return $this->executeQuickOrderFormHandlerStrategyPlugin($quickOrderForm, $request);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $quickOrderForm
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|null
     */
    protected function executeQuickOrderFormHandlerStrategyPlugin(FormInterface $quickOrderForm, Request $request): ?RedirectResponse
    {
        $routeTransfer = null;
        foreach ($this->getFactory()->getQuickOrderFormHandlerStrategyPlugins() as $quickOrderFormHandlerStrategyPlugin) {
            if (!$quickOrderFormHandlerStrategyPlugin->isApplicable($quickOrderForm->getData(), $request->attributes->all())) {
                continue;
            }
            $routeTransfer = $quickOrderFormHandlerStrategyPlugin->execute($quickOrderForm->getData(), $request->attributes->all());
            break;
        }

        if ($routeTransfer === null) {
            return null;
        }

        return new RedirectResponse($this->getApplication()->path($routeTransfer->getRoute(), $routeTransfer->getParameters()));
    }

    /**
     * @param array $orderItems
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    protected function getQuickOrderTransfer(array $orderItems = []): QuickOrderTransfer
    {
        return $this->getFactory()
            ->createQuickOrderFormDataProvider()
            ->getQuickOrderTransfer($orderItems);
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer[] $quickOrderItemTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteStorageTransfer[]
     */
    protected function getProductConcretesStorageTransfers(array $quickOrderItemTransfers): array
    {
        $skus = array_map(function (QuickOrderItemTransfer $quickOrderItemTransfer) {
            return $quickOrderItemTransfer->getSku();
        }, $quickOrderItemTransfers);
        $skus = array_filter($skus);

        $productConcreteStorageTransfers = [];
        foreach ($skus as $sku) {
            $productConcreteStorageTransfers[$sku] = $this->getFactory()
                ->getProductStorageClient()
                ->findProductConcreteStorageDataByMappingForCurrentLocale('sku', $sku);
        }

        return $productConcreteStorageTransfers;
    }
}
