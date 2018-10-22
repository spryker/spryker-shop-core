<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Controller;

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
        $products = $this->getProductsByQuickOrder($quickOrderTransfer);

        $quickOrderTransfer = $this
            ->getFactory()
            ->createQuickOrderItemPluginExecutor()
            ->applyQuickOrderItemFilterPluginsOnQuickOrder($quickOrderTransfer, $products);
        $quickOrderTransfer = $this->getFactory()
            ->createPriceResolver()
            ->setSumPriceForQuickOrderTransfer($quickOrderTransfer);

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
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    protected function getProductsByQuickOrder(QuickOrderTransfer $quickOrderTransfer): array
    {
        $products = $this->getFactory()
            ->createProductResolver()
            ->getProductsByQuickOrder($quickOrderTransfer);

        foreach ($products as $sku => $product) {
            $products[$sku] = $this->getFactory()
                ->getQuickOrderClient()
                ->expandProductConcrete($product);
        }

        return $products;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    protected function getProductByQuickOrderItem(QuickOrderItemTransfer $quickOrderItemTransfer): ?ProductConcreteTransfer
    {
        if (empty($quickOrderItemTransfer->getSku())) {
            return null;
        }

        $product = $this->getFactory()
            ->createProductResolver()
            ->getProductBySku($quickOrderItemTransfer->getSku());

        $product = $this->getFactory()
            ->getQuickOrderClient()
            ->expandProductConcrete($product);

        return $product;
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

        $quickOrderTransfer = $quickOrderFormDataProvider->mapFormDataToQuickOrderItems($formDataItems);
        $quickOrderTransfer = $quickOrderFormDataProvider->appendEmptyQuickOrderItems($quickOrderTransfer);

        $quickOrderForm = $this->getFactory()
            ->createQuickOrderFormFactory()
            ->getQuickOrderForm($quickOrderTransfer);

        $products = $this->getProductsByQuickOrder($quickOrderTransfer);
        return [
            'form' => $quickOrderForm->createView(),
            'additionalColumns' => $this->mapAdditionalQuickOrderFormColumnPluginsToArray(),
            'products' => $products,
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

        $products = $this->getProductsByQuickOrder($quickOrderTransfer);
        return [
            'form' => $quickOrderForm->createView(),
            'additionalColumns' => $this->mapAdditionalQuickOrderFormColumnPluginsToArray(),
            'products' => $products,
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

        $product = $this->getProductByQuickOrderItem($quickOrderItemTransfer);

        $quickOrderItemTransfer = $this->getFactory()
            ->createQuickOrderItemPluginExecutor()
            ->applyQuickOrderItemFilterPluginsOnQuickOrderItem($quickOrderItemTransfer, $product);

        $quickOrderItemTransfer = $this->getFactory()
            ->createPriceResolver()
            ->setSumPriceForQuickOrderItemTransfer($quickOrderItemTransfer);

        $viewData = [
            'quantity' => $quickOrderItemTransfer->getQuantity(),
            'price' => $quickOrderItemTransfer->getSumPrice(),
            'additionalColumns' => $this->mapAdditionalQuickOrderFormColumnPluginsToArray(),
            'product' => $product,
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

        $quickOrderTransfer = $this->getFactory()
            ->createQuickOrderFormDataProvider()
            ->getQuickOrderTransfer($quickOrderItems);

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
}
