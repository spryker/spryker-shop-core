<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Controller;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider;
use SprykerShop\Yves\CheckoutPage\Plugin\Provider\CheckoutPageControllerProvider;
use SprykerShop\Yves\QuickOrderPage\Form\QuickOrderForm;
use SprykerShop\Yves\QuickOrderPage\Form\TextOrderForm;
use SprykerShop\Yves\QuickOrderPage\Form\UploadOrderForm;
use SprykerShop\Yves\QuickOrderPage\Plugin\Provider\QuickOrderPageControllerProvider;
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
    use PermissionAwareTrait;

    public const PARAM_ROW_INDEX = 'row-index';
    public const PARAM_QUICK_ORDER_FORM = 'quick_order_form';
    protected const PARAM_QUICK_ORDER_FILE_TYPE = 'file-type';
    protected const MESSAGE_CLEAR_ALL_ROWS_SUCCESS = 'quick-order.message.success.the-form-items-have-been-successfully-cleared';
    protected const ERROR_MESSAGE_QUANTITY_INVALID = 'quick-order.errors.quantity-invalid';
    protected const MESSAGE_TYPE_WARNING = 'warning';
    protected const MESSAGE_PERMISSION_FAILED = 'global.permission.failed';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        $response = $this->executeQuickOrderFormSubmitAction($request);

        if ($response) {
            return $response;
        }

        $response = $this->executeIndexAction($request);

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
    protected function executeQuickOrderFormSubmitAction(Request $request)
    {
        $quickOrderTransfer = $this->getFactory()
            ->createQuickOrderFormDataProvider()
            ->getQuickOrderTransfer();

        $quickOrderForm = $this->getFactory()
            ->createQuickOrderFormFactory()
            ->getQuickOrderForm($quickOrderTransfer)
            ->handleRequest($request);

        if ($quickOrderForm->isSubmitted() && $quickOrderForm->isValid()) {
            $response = $this->processQuickOrderForm($quickOrderForm, $request);

            if ($response !== null) {
                return $response;
            }
        }

        return [];
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
            ->getTextOrderForm();

        $uploadOrderForm = $this->getFactory()
            ->createQuickOrderFormFactory()
            ->getUploadOrderForm();

        $handledQuickOrderItems = $this->handleQuickOrderForm($request);
        $handledUploadOrderItems = $this->handleUploadOrderForm($request, $uploadOrderForm);
        $handledTextOrderItems = $this->handleTextOrderForm($request, $textOrderForm);

        $quickOrderItems = array_merge(
            $handledQuickOrderItems,
            $handledUploadOrderItems,
            $handledTextOrderItems
        );

        if (count($handledUploadOrderItems) || count($handledTextOrderItems)) {
            $quickOrderItems = $this->filterQuickOrderItems($quickOrderItems);
        }

        $quickOrderTransfer = $this->getQuickOrderTransfer($quickOrderItems);

        $quickOrderForm = $this->getFactory()
            ->createQuickOrderFormFactory()
            ->getQuickOrderForm($quickOrderTransfer);

        $prices = $this->getProductPricesFromQuickOrderTransfer($quickOrderTransfer);

        $products = $this->getProductsFromQuickOrderItems($quickOrderTransfer);

        $fileTemplateExtensions = $this->getFactory()
            ->createFileTemplateExtensionsReader()
            ->getFileTemplateExtensions();

        $additionalColumns = $this->getFactory()
            ->createAdditionalColumnsProvider()
            ->getAdditionalColumns();

        return [
            'quickOrderForm' => $quickOrderForm->createView(),
            'textOrderForm' => $textOrderForm->createView(),
            'uploadOrderForm' => $uploadOrderForm->createView(),
            'additionalColumns' => $additionalColumns,
            'products' => $this->transformProductsViewData($products),
            'fileTemplateExtensions' => $fileTemplateExtensions,
            'prices' => $prices, // @deprecated quickOrderForm already contains this data per row at sumPrice property.
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer[] $quickOrderItems
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    protected function getQuickOrderTransfer(array $quickOrderItems): QuickOrderTransfer
    {
        $quickOrderTransfer = $this->getFactory()
            ->createQuickOrderFormDataProvider()
            ->getQuickOrderTransfer($quickOrderItems);

        $quickOrderTransfer = $this->getFactory()
            ->getQuickOrderClient()
            ->buildQuickOrderTransfer($quickOrderTransfer);

        $quickOrderTransfer = $this->getFactory()
            ->createQuickOrderItemPluginExecutor()
            ->applyQuickOrderItemFilterPluginsOnQuickOrder($quickOrderTransfer);

        $quickOrderTransfer = $this->getFactory()
            ->createPriceResolver()
            ->setSumPriceForQuickOrderTransfer($quickOrderTransfer);

        return $quickOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer[] $quickOrderItems
     *
     * @return array
     */
    protected function filterQuickOrderItems(array $quickOrderItems): array
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadTemplateAction(Request $request): Response
    {
        return $this->executeDownloadTemplateAction($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function executeDownloadTemplateAction(Request $request): Response
    {
        return $this->getFactory()
            ->createFileDownloadRenderer()
            ->render($request->get(static::PARAM_QUICK_ORDER_FILE_TYPE, ''));
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    protected function getProductsFromQuickOrderItems(QuickOrderTransfer $quickOrderTransfer): array
    {
        $productConcreteTransfers = [];
        foreach ($quickOrderTransfer->getItems() as $orderItem) {
            if ($orderItem->getProductConcrete()) {
                $productConcreteTransfers[] = $orderItem->getProductConcrete();
            }
        }

        return $productConcreteTransfers;
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

        $quickOrderTransfer = $this->getFactory()
            ->getQuickOrderClient()
            ->buildQuickOrderTransfer($quickOrderTransfer);

        $products = $this->getProductsFromQuickOrderItems($quickOrderTransfer);
        $prices = $this->getProductPricesFromQuickOrderTransfer($quickOrderTransfer);

        $additionalColumns = $this->getFactory()
            ->createAdditionalColumnsProvider()
            ->getAdditionalColumns();

        return [
            'form' => $quickOrderForm->createView(),
            'additionalColumns' => $additionalColumns,
            'products' => $this->transformProductsViewData($products),
            'prices' => $prices, // @deprecated quickOrderForm already contains this data per row at sumPrice property.
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
            throw new HttpException(Response::HTTP_BAD_REQUEST, '"row-index" is out of the bound.');
        }
        unset($formDataItems[$rowIndex]);

        $quickOrderTransfer = $this->getFactory()
            ->createQuickOrderFormDataProvider()
            ->mapFormDataToQuickOrderItems($formDataItems);

        $quickOrderForm = $this->getFactory()
            ->createQuickOrderFormFactory()
            ->getQuickOrderForm($quickOrderTransfer);

        $quickOrderTransfer = $this->getFactory()
            ->getQuickOrderClient()
            ->buildQuickOrderTransfer($quickOrderTransfer);

        $products = $this->getProductsFromQuickOrderItems($quickOrderTransfer);
        $prices = $this->getProductPricesFromQuickOrderTransfer($quickOrderTransfer);

        $additionalColumns = $this->getFactory()
            ->createAdditionalColumnsProvider()
            ->getAdditionalColumns();

        return [
            'form' => $quickOrderForm->createView(),
            'additionalColumns' => $additionalColumns,
            'products' => $this->transformProductsViewData($products),
            'prices' => $prices, // @deprecated quickOrderForm already contains this data per row at sumPrice property.
        ];
    }

    /**
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function clearAllRowsAction()
    {
        $this->addSuccessMessage(static::MESSAGE_CLEAR_ALL_ROWS_SUCCESS);

        return $this->redirectResponseInternal(QuickOrderPageControllerProvider::ROUTE_QUICK_ORDER);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productAdditionalDataAction(Request $request)
    {
        $quantity = (int)$request->get('quantity', 1);
        $sku = $request->query->get('sku');
        $index = $request->query->get('index');

        $quickOrderItemTransfer = (new QuickOrderItemTransfer())->setSku($sku);

        if ($quantity < 1) {
            $quantity = 1;
            $this->addMessageToQuickOrderItemTransfer($quickOrderItemTransfer);
        }

        $maxAllowedQuantity = $this->getFactory()
            ->getModuleConfig()
            ->getMaxAllowedQuantity();
        if ($quantity > $maxAllowedQuantity) {
            $quantity = $maxAllowedQuantity;
            $this->addMessageToQuickOrderItemTransfer($quickOrderItemTransfer);
        }

        $quickOrderItemTransfer->setQuantity($quantity);
        $quickOrderTransfer = $this->getQuickOrderTransfer([$quickOrderItemTransfer]);
        $quickOrderItemTransfer = $quickOrderTransfer->getItems()->offsetGet(0);
        $form = $this->getFactory()
            ->createQuickOrderFormFactory()
            ->getQuickOrderItemEmbeddedForm($quickOrderItemTransfer);

        $products = $this->getProductsFromQuickOrderItems($quickOrderTransfer);
        $products = $this->transformProductsViewData($products);

        $additionalColumns = $this->getFactory()
            ->createAdditionalColumnsProvider()
            ->getAdditionalColumns();

        $viewData = [
            'price' => $quickOrderItemTransfer->getSumPrice(),
            'additionalColumns' => $additionalColumns,
            'product' => reset($products),
            'form' => $form->createView(),
            'messages' => $quickOrderItemTransfer->getMessages(),
            'index' => $index,
        ];

        return $this->view(
            $viewData,
            $this->getFactory()->getQuickOrderPageWidgetPlugins(),
            '@QuickOrderPage/views/quick-order-row-async/quick-order-row-async.twig'
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer
     */
    protected function addMessageToQuickOrderItemTransfer(QuickOrderItemTransfer $quickOrderItemTransfer): QuickOrderItemTransfer
    {
        return $quickOrderItemTransfer->addMessage(
            (new MessageTransfer())
                ->setType(static::MESSAGE_TYPE_WARNING)
                ->setValue(static::ERROR_MESSAGE_QUANTITY_INVALID)
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $textOrderForm
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer[]
     */
    protected function handleTextOrderForm(Request $request, FormInterface $textOrderForm): array
    {
        $quickOrderItems = [];

        if ($request->get(TextOrderForm::SUBMIT_BUTTON_TEXT_ORDER) !== null) {
            $textOrderForm->handleRequest($request);
        }

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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $uploadOrderForm
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer[]
     */
    protected function handleUploadOrderForm(Request $request, FormInterface $uploadOrderForm): array
    {
        $quickOrderItems = [];

        if ($request->get(UploadOrderForm::SUBMIT_BUTTON_UPLOAD_ORDER) !== null) {
            $uploadOrderForm->handleRequest($request);
        }

        if ($uploadOrderForm->isSubmitted() && $uploadOrderForm->isValid()) {
            $data = $uploadOrderForm->getData();
            $quickOrderItems = $this->getFactory()
                ->createUploadedFileParser()
                ->parse($data[UploadOrderForm::FIELD_FILE_UPLOAD_ORDER]);
        }

        return $quickOrderItems;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer[]
     */
    protected function handleQuickOrderForm($request): array
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
    protected function processQuickOrderForm(FormInterface $quickOrderForm, Request $request): ?RedirectResponse
    {
        $quickOrderTransfer = $quickOrderForm->getData();

        $quickOrderTransfer = $this->getFactory()
            ->getQuickOrderClient()
            ->buildQuickOrderTransfer($quickOrderTransfer);

        if ($request->get(QuickOrderForm::SUBMIT_BUTTON_ADD_TO_CART) !== null) {
            return $this->executeAddToCartAction($quickOrderTransfer);
        }

        if ($request->get(QuickOrderForm::SUBMIT_BUTTON_CREATE_ORDER) !== null) {
            return $this->executeCreateOrderAction($quickOrderTransfer);
        }

        return $this->executeQuickOrderFormHandlerStrategyPlugin($quickOrderForm, $request);
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|null
     */
    protected function executeAddToCartAction(QuickOrderTransfer $quickOrderTransfer): ?RedirectResponse
    {
        if (!$this->can('AddCartItemPermissionPlugin')) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return null;
        }

        $result = $this->getFactory()
            ->createFormOperationHandler()
            ->addToCart($quickOrderTransfer);

        if (!$result) {
            return null;
        }

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|null
     */
    protected function executeCreateOrderAction(QuickOrderTransfer $quickOrderTransfer): ?RedirectResponse
    {
        if (!$this->can('AddCartItemPermissionPlugin')) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return null;
        }

        $result = $this->getFactory()
            ->createFormOperationHandler()
            ->addToEmptyCart($quickOrderTransfer);

        if (!$result) {
            return null;
        }

        return $this->redirectResponseInternal(CheckoutPageControllerProvider::CHECKOUT_INDEX);
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

        if ($response === null || !$response->getRoute()) {
            return null;
        }

        $route = $response->getRoute();

        return new RedirectResponse($this->getApplication()->path($route->getRoute(), $route->getParameters()));
    }

    /**
     * @deprecated Will be removed without replacement.
     *
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
    protected function transformProductsViewData(array $productConcreteTransfers): array
    {
        return $this->getFactory()
            ->createViewDataTransformer()
            ->transformProductData($productConcreteTransfers, $this->getFactory()->getQuickOrderFormColumnPlugins());
    }
}
