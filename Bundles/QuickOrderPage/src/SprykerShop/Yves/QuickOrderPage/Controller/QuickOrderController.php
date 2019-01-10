<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Controller;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;
use SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider;
use SprykerShop\Yves\CheckoutPage\Plugin\Provider\CheckoutPageControllerProvider;
use SprykerShop\Yves\QuickOrderPage\Form\QuickOrderForm;
use SprykerShop\Yves\QuickOrderPage\Form\TextOrderForm;
use SprykerShop\Yves\QuickOrderPage\Form\UploadOrderForm;
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
    public const PARAM_QUICK_ORDER_FORM_SUBMIT_ACTION = 'submitForm';
    public const PARAM_QUICK_ORDER_FILE_TYPE = 'file-type';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        if ($request->get(QuickOrderForm::SUBMIT_BUTTON_CREATE_ORDER) !== null
            || $request->get(QuickOrderForm::SUBMIT_BUTTON_ADD_TO_CART) !== null) {
            $response = $this->executeQuickFormSubmitAction($request);
        } else {
            $response = $this->executeIndexAction($request);
        }

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
    protected function executeQuickFormSubmitAction(Request $request)
    {
        $quickOrderTransfer = $this->getFactory()
                                   ->createQuickOrderFormDataProvider()
                                   ->getQuickOrderTransfer();

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

        $uploadOrderForm = $this->getFactory()
                                ->createQuickOrderFormFactory()
                                ->getUploadOrderForm()
                                ->handleRequest($request);

        $handledQuickOrderItemsForm = $this->handleQuickOrderItemsForm($request);
        $handledUploadOrderForm = $this->handleUploadOrderForm($uploadOrderForm);
        $handledTextOrderForm = $this->handleTextOrderForm($textOrderForm);

        $quickOrderItems = array_merge(
            $handledQuickOrderItemsForm,
            $handledUploadOrderForm,
            $handledTextOrderForm
        );

        if (count($handledUploadOrderForm) || count($handledTextOrderForm)) {
            $quickOrderItems = $this->filterItems($quickOrderItems);
        }

        $quickOrderTransfer = $this->getFactory()
                                   ->createQuickOrderFormDataProvider()
                                   ->getQuickOrderTransfer($quickOrderItems);

        $quickOrderTransfer = $this->getProductsByQuickOrder($quickOrderTransfer);
        $quickOrderTransfer = $this->getFactory()
            ->createQuickOrderItemPluginExecutor()
            ->applyQuickOrderItemFilterPluginsOnQuickOrder($quickOrderTransfer);

        $quickOrderTransfer = $this->getFactory()
            ->createPriceResolver()
            ->setSumPriceForQuickOrderTransfer($quickOrderTransfer);

        $quickOrderForm = $this->getFactory()
            ->createQuickOrderFormFactory()
            ->getQuickOrderForm($quickOrderTransfer);

        $prices = $this->getProductPricesFromQuickOrderTransfer($quickOrderTransfer);
        $products = $this->getProductsFromQuickOrderItems($quickOrderTransfer);

        return [
            'quickOrderForm' => $quickOrderForm->createView(),
            'textOrderForm' => $textOrderForm->createView(),
            'uploadOrderForm' => $uploadOrderForm->createView(),
            'additionalColumns' => $this->mapAdditionalQuickOrderFormColumnPluginsToArray(),
            'products' => $this->transformProductsViewData($products),
            'fileTemplatesUrls' => $this->getFileTemplatesUrls(),
            'prices' => $prices,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer[] $quickOrderItems
     *
     * @return array
     */
    protected function filterItems(array $quickOrderItems): array
    {
        $filteredItems = [];
        foreach ($quickOrderItems as $quickOrderItem) {
            if (empty($quickOrderItem->getSku()) && empty($quickOrderItem->getQuantity())) {
                continue;
            }

            $filteredItems[] = $quickOrderItem;
        }

        return $filteredItems;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function downloadTemplateAction(Request $request)
    {
        $this->executeDownloadTemplateAction($request);

        return $this->view(
            [],
            $this->getFactory()->getQuickOrderPageWidgetPlugins(),
            '@QuickOrderPage/views/quick-order/quick-order.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    protected function executeDownloadTemplateAction(Request $request): void
    {
        $this->getFactory()
             ->createFileOutputter()
             ->outputFile($request->get(static::PARAM_QUICK_ORDER_FILE_TYPE));
    }

    /**
     * @return string[]
     */
    protected function getFileTemplatesUrls(): array
    {
        $fileTemplatesUrls = [];
        foreach ($this->getFactory()->getQuickOrderFileTemplatePlugins() as $fileTemplatePlugin) {
            $fileTemplatesUrls[$fileTemplatePlugin->getFileExtension()] = $fileTemplatePlugin->getFileExtension();
        }

        return $fileTemplatesUrls;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    protected function getProductsFromQuickOrderItems(QuickOrderTransfer $quickOrderTransfer): array
    {
        $concreteProductsTransfers = [];
        foreach ($quickOrderTransfer->getItems() as $orderItem) {
            if ($orderItem->getProductConcrete()) {
                $concreteProductsTransfers[] = $orderItem->getProductConcrete();
            }
        }

        return $concreteProductsTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    protected function getProductsByQuickOrder(QuickOrderTransfer $quickOrderTransfer): QuickOrderTransfer
    {
        $quickOrderTransfer = $this->getFactory()
            ->getQuickOrderClient()
            ->getProductsByQuickOrder($quickOrderTransfer);

        $quickOrderTransfer = $this->getFactory()
             ->getQuickOrderClient()
             ->validateQuickOrderTransfer($quickOrderTransfer);

        foreach ($quickOrderTransfer->getItems() as $orderItemTransfer) {
            if (!empty($orderItemTransfer->getErrorMessage())) {
                continue;
            }

            if (!$orderItemTransfer->getProductConcrete()
                || !$orderItemTransfer->getProductConcrete()->getIdProductConcrete()) {
                continue;
            }

            $expandedProductConcrete = $this->getFactory()
                                                 ->getQuickOrderClient()
                                                 ->expandProductConcreteTransfers([$orderItemTransfer->getProductConcrete()]);
            $orderItemTransfer->setProductConcrete($expandedProductConcrete[0]);
        }

        return $quickOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    protected function getProductByQuickOrderItem(QuickOrderItemTransfer $quickOrderItemTransfer): ?ProductConcreteTransfer
    {
        if (!$quickOrderItemTransfer->getSku()) {
            return null;
        }

        $productConcreteTransfer = $this->getFactory()
            ->createProductResolver()
            ->getProductBySku($quickOrderItemTransfer->getSku());

        [$productConcreteTransfer] = $this->getFactory()
            ->getQuickOrderClient()
            ->expandProductConcreteTransfers([$productConcreteTransfer]);

        return $productConcreteTransfer;
    }

    /**
     * @return array
     */
    protected function mapAdditionalQuickOrderFormColumnPluginsToArray(): array
    {
        $additionalColumns = [];
        foreach ($this->getFactory()->getQuickOrderFormColumnPlugins() as $additionalColumnPlugin) {
            $additionalColumns[] = [
                'title' => $additionalColumnPlugin->getColumnTitle(),
                'dataPath' => $additionalColumnPlugin->getDataPath(),
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

        $quickOrderTransfer = $quickOrderFormDataProvider->mapFormDataToQuickOrderItems($formDataItems);
        $quickOrderTransfer = $quickOrderFormDataProvider->appendEmptyQuickOrderItems($quickOrderTransfer);

        $quickOrderForm = $this->getFactory()
            ->createQuickOrderFormFactory()
            ->getQuickOrderForm($quickOrderTransfer);

        $quickOrderTransfer = $this->getProductsByQuickOrder($quickOrderTransfer);
        $products = $this->getProductsFromQuickOrderItems($quickOrderTransfer);
        $prices = $this->getProductPricesFromQuickOrderTransfer($quickOrderTransfer);

        return [
            'form' => $quickOrderForm->createView(),
            'additionalColumns' => $this->mapAdditionalQuickOrderFormColumnPluginsToArray(),
            'products' => $this->transformProductsViewData($products),
            'prices' => $prices,
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

        $quickOrderTransfer = $this->getFactory()
            ->createQuickOrderFormDataProvider()
            ->mapFormDataToQuickOrderItems($formDataItems);

        $quickOrderForm = $this->getFactory()
            ->createQuickOrderFormFactory()
            ->getQuickOrderForm($quickOrderTransfer);

        $quickOrderTransfer = $this->getProductsByQuickOrder($quickOrderTransfer);
        $products = $this->getProductsFromQuickOrderItems($quickOrderTransfer);
        $prices = $this->getProductPricesFromQuickOrderTransfer($quickOrderTransfer);

        return [
            'form' => $quickOrderForm->createView(),
            'additionalColumns' => $this->mapAdditionalQuickOrderFormColumnPluginsToArray(),
            'products' => $this->transformProductsViewData($products),
            'prices' => $prices,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productAdditionalDataAction(Request $request)
    {
        $quantity = $request->get('quantity');
        $sku = $request->query->get('sku');
        $index = $request->query->get('index');

        $quickOrderItemTransfer = (new QuickOrderItemTransfer())
            ->setQuantity($quantity ?: 1)
            ->setSku($sku);

        $product = $this->getProductByQuickOrderItem($quickOrderItemTransfer);

        $quickOrderItemTransfer = $this->getFactory()
            ->createQuickOrderItemPluginExecutor()
            ->applyQuickOrderItemFilterPluginsOnQuickOrderItem($quickOrderItemTransfer);

        $quickOrderItemTransfer = $this->getFactory()
            ->createPriceResolver()
            ->setSumPriceForQuickOrderItemTransfer($quickOrderItemTransfer);

        $form = $this->getFactory()
            ->createQuickOrderFormFactory()
            ->getQuickOrderItemEmbeddedForm($quickOrderItemTransfer);

        $product = $this->transformProductsViewData([$product])[$sku] ?? null;

        $viewData = [
            'price' => $quickOrderItemTransfer->getSumPrice(),
            'additionalColumns' => $this->mapAdditionalQuickOrderFormColumnPluginsToArray(),
            'product' => $product,
            'form' => $form->createView(),
            'index' => $index,
            'isQuantityAdjusted' => $this->getIsQuantityAdjusted($quantity, $quickOrderItemTransfer->getQuantity()),
        ];

        return $this->view(
            $viewData,
            $this->getFactory()->getQuickOrderPageWidgetPlugins(),
            '@QuickOrderPage/views/quick-order-row-async/quick-order-row-async.twig'
        );
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $textOrderForm
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer[]
     */
    protected function handleTextOrderForm(FormInterface $textOrderForm): array
    {
        $quickOrderItems = [];
        if ($textOrderForm->isSubmitted() && $textOrderForm->isValid()) {
            $data = $textOrderForm->getData();

            if (($data[TextOrderForm::FIELD_TEXT_ORDER] !== null)) {
                $quickOrderItems = $this->getFactory()
                                        ->createTextOrderParser()
                                        ->parse($data[TextOrderForm::FIELD_TEXT_ORDER]);
            }
        }

        return $quickOrderItems;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $uploadOrderForm
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer[]
     */
    protected function handleUploadOrderForm(FormInterface $uploadOrderForm): array
    {
        $quickOrderItems = [];
        if ($uploadOrderForm->isSubmitted()) {
            $data = $uploadOrderForm->getData();
            $quickOrderItems = $this->getFactory()
                                    ->createUploadOrderParser()
                                    ->parse($data[UploadOrderForm::FIELD_FILE_UPLOAD_ORDER]);
        }

        return $quickOrderItems;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer[]
     */
    protected function handleQuickOrderItemsForm($request): array
    {
        $quickOrderItems = [];
        $formData = $request->get(static::PARAM_QUICK_ORDER_FORM);
        $formDataItems = $formData['items'] ?? [];
        if ($formDataItems) {
            $quickOrderTransfer = $this->getFactory()
                                       ->createQuickOrderFormDataProvider()
                                       ->mapFormDataToQuickOrderItems($formDataItems);
            $quickOrderItems = (array)$quickOrderTransfer->getItems();
        }

        return $quickOrderItems;
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
        $response = null;
        foreach ($this->getFactory()->getQuickOrderFormHandlerStrategyPlugins() as $quickOrderFormHandlerStrategyPlugin) {
            if (!$quickOrderFormHandlerStrategyPlugin->isApplicable($quickOrderForm->getData(), $request->request->all())) {
                continue;
            }
            $response = $quickOrderFormHandlerStrategyPlugin->execute($quickOrderForm->getData(), $request->request->all());
            break;
        }

        if ($response === null) {
            return null;
        }

        $route = $response->getRoute();

        return new RedirectResponse($this->getApplication()->path($route->getRoute(), $route->getParameters()));
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return int[]
     */
    protected function getProductPricesFromQuickOrderTransfer(QuickOrderTransfer $quickOrderTransfer): array
    {
        $prices = [];
        $quickOrderTransfer = $this->getFactory()
            ->createPriceResolver()
            ->setSumPriceForQuickOrderTransfer($quickOrderTransfer);

        foreach ($quickOrderTransfer->getItems() as $quickOrderItemTransfer) {
            $sku = $quickOrderItemTransfer->getSku();

            if ($sku !== null) {
                $prices[$sku] = $quickOrderItemTransfer->getSumPrice();
            }
        }

        return $prices;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return array
     */
    protected function indexProductsBySku(array $productConcreteTransfers): array
    {
        $products = [];

        foreach ($productConcreteTransfers as $product) {
            $products[$product->getSku()] = $product;
        }

        return $products;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return array
     */
    protected function transformProductsViewData(array $productConcreteTransfers): array
    {
        return $this->getFactory()
            ->createViewDataTransformer()
            ->transformProductData($productConcreteTransfers, $this->getFactory()->getQuickOrderFormColumnPlugins());
    }

    /**
     * @param mixed $before
     * @param mixed $after
     *
     * @return bool
     */
    protected function getIsQuantityAdjusted($before, $after): bool
    {
        return $before !== null && (int)$before !== (int)$after;
    }
}
