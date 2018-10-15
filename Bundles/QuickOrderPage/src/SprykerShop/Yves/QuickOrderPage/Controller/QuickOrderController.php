<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Controller;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;
use Spryker\Client\PriceProductStorage\PriceProductStorageClient;
use Spryker\Client\ProductStorage\ProductStorageClient;
use SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider;
use SprykerShop\Yves\CheckoutPage\Plugin\Provider\CheckoutPageControllerProvider;
use SprykerShop\Yves\QuickOrderPage\Form\QuickOrderForm;
use SprykerShop\Yves\QuickOrderPage\Form\TextOrderForm;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

        $textOrderParsedItems = $this->handleTextOrderForm($textOrderForm);
        $quickOrder = $this->getQuickOrderTransfer($textOrderParsedItems);

        $quickOrderForm = $this->getFactory()
            ->createQuickOrderFormFactory()
            ->getQuickOrderForm($quickOrder)
            ->handleRequest($request);

        if ($quickOrderForm->isSubmitted() && $quickOrderForm->isValid()) {
            $response = $this->handleQuickOrderForm($quickOrderForm, $request);

            if ($response !== null) {
                return $response;
            }
        }

        return [
            'itemsForm' => $quickOrderForm->createView(),
            'textOrderForm' => $textOrderForm->createView(),
            'additionalColumns' => $this->mapQuickOrderFormAdditionalDataColumnProviderPluginsToArray(),
        ];
    }

    /**
     * @return array
     */
    protected function mapQuickOrderFormAdditionalDataColumnProviderPluginsToArray()
    {
        $additionalColumns = [];

        foreach ($this->getQuickOrderFormAdditionalDataColumnProviderPlugins() as $additionalColumn)
        {
            $additionalColumns[] = [
                'fieldName' => $additionalColumn->getFieldName(),
                'title' => $additionalColumn->getColumnTitle(),
            ];
        }

        return $additionalColumns;
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFormAdditionalDataColumnProviderPluginInterface[]
     */
    protected function getQuickOrderFormAdditionalDataColumnProviderPlugins()
    {
        $quickOrderFormAdditionalDataColumnProviderPluginCollection = [];

        foreach ($this->getFactory()->getQuickOrderFormAdditionalDataColumnProviderPlugins() as $quickOrderFormAdditionalDataColumnProviderPlugin) {
            $quickOrderFormAdditionalDataColumnProviderPluginCollection[$quickOrderFormAdditionalDataColumnProviderPlugin->getFieldName()] = $quickOrderFormAdditionalDataColumnProviderPlugin;
        }

        return $quickOrderFormAdditionalDataColumnProviderPluginCollection;
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

        return [
            'form' => $quickOrderForm->createView(),
            'additionalColumns' => $this->mapQuickOrderFormAdditionalDataColumnProviderPluginsToArray(),
            'productConcretesData' => $this->getProductConcretesData($orderItems),
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

        $orderItems = $this->getFactory()
            ->createQuickOrderFormDataProvider()
            ->getOrderItemsFromFormData($formDataItems);

        $quickOrder = $this->getQuickOrderTransfer($orderItems);

        $quickOrderForm = $this->getFactory()
            ->createQuickOrderFormFactory()
            ->getQuickOrderForm($quickOrder);

        return [
            'form' => $quickOrderForm->createView(),
            'additionalColumns' => $this->mapQuickOrderFormAdditionalDataColumnProviderPluginsToArray(),
            'productConcretesData' => $this->getProductConcretesData($orderItems),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productAdditionalDataAction(Request $request)
    {
        $productConcreteTransfer = $this->executeProductAdditionalDataAction($request);

        // return $this->jsonResponse($productConcreteTransfer->toArray(true, true));
        return $this->view(
            $productConcreteTransfer->toArray(true, true),
            $this->getFactory()->getQuickOrderPageWidgetPlugins(),
            '@QuickOrderPage/views/quick-order-row-async/quick-order-row-async.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function executeProductAdditionalDataAction(Request $request): ProductConcreteTransfer
    {
        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setIdProductConcrete(
            $request->query->getInt(static::PARAM_ID_PRODUCT)
        );

        $productConcreteTransfer = $this->getFactory()
            ->getQuickOrderClient()
            ->expandProductConcrete($productConcreteTransfer);

        return $productConcreteTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productPriceAction(Request $request)
    {
        if (!$request->query->getInt(static::PARAM_ID_PRODUCT) || !$request->query->getInt(static::PARAM_ID_PRODUCT_ABSTRACT)) {
            return $this->jsonResponse(
                (new CurrentProductPriceTransfer())->toArray(true, true),
                Response::HTTP_BAD_REQUEST
            );
        }

        $currentProductPriceTransfer = $this->executeProductPriceAction($request);

        return $this->jsonResponse($currentProductPriceTransfer->toArray(true, true));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    protected function executeProductPriceAction(Request $request): CurrentProductPriceTransfer
    {
        $priceProductFilterTransfer = $this->createPriceProductFilterTransfer($request);

        // TODO inject properly
        $priceProductStorageClient = new PriceProductStorageClient();

        return $priceProductStorageClient
            ->getResolvedCurrentProductPriceTransfer($priceProductFilterTransfer);
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
                ->createOrder($quickOrder);

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
        foreach ($this->getFactory()->getQuickOrderFormHandlerStrategyPlugins() as $quickOrderFormHandlerStrategyPlugin) {
            if (!$quickOrderFormHandlerStrategyPlugin->isApplicable($quickOrderForm, $request)) {
                continue;
            }
            return $quickOrderFormHandlerStrategyPlugin->execute($quickOrderForm, $request);
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

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer[] $quickOrderItemTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteStorageTransfer[]
     */
    protected function getProductConcretesData(array $quickOrderItemTransfers): array
    {
        // TODO: inject properly
        $productStorageClient = new ProductStorageClient();

        $productIds = array_map(function(QuickOrderItemTransfer $quickOrderItemTransfer) {
            return $quickOrderItemTransfer->getIdProductConcrete();
        }, $quickOrderItemTransfers);

        $productConcreteStorageTransfers = $productStorageClient
            ->getProductConcreteStorageTransfers($productIds);

        $mappedProductConcreteStorageTransfers = [];
        foreach ($productConcreteStorageTransfers as $productConcreteStorageTransfer) {
            $mappedProductConcreteStorageTransfers[$productConcreteStorageTransfer->getIdProductConcrete()] = $productConcreteStorageTransfer;
        }

        return $mappedProductConcreteStorageTransfers;
    }
}
