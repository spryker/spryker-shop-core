<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Controller;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerShop\Shared\CustomerPage\CustomerPageConfig;
use SprykerShop\Yves\CustomerPage\Form\OrderSearchForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageConfig getConfig()
 */
class OrderController extends AbstractCustomerController
{
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CustomerPage\CustomerPageConfig::getDefaultOrderHistoryPerPage()} instead.
     *
     * @var int
     */
    public const ORDER_LIST_LIMIT = 10;

    /**
     * @deprecated Use {@link \SprykerShop\Yves\CustomerPage\CustomerPageConfig::getDefaultOrderHistorySortField()} instead.
     *
     * @var string
     */
    public const ORDER_LIST_SORT_FIELD = 'created_at';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\CustomerPage\CustomerPageConfig::getDefaultOrderHistorySortDirection()} instead.
     *
     * @var string
     */
    public const ORDER_LIST_SORT_DIRECTION = 'DESC';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\CustomerPage\Reader\OrderReader::PARAM_PAGE} instead.
     *
     * @var string
     */
    public const PARAM_PAGE = 'page';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\CustomerPage\Reader\OrderReader::DEFAULT_PAGE} instead.
     *
     * @var int
     */
    public const DEFAULT_PAGE = 1;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $viewData = $this->executeIndexAction($request);

        return $this->view(
            $viewData,
            $this->getFactory()->getCustomerOrderListWidgetPlugins(),
            '@CustomerPage/views/order/order.twig',
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeIndexAction(Request $request): array
    {
        $orderListTransfer = new OrderListTransfer();
        $customerPageFactory = $this->getFactory();
        $isOrderSearchEnabled = $customerPageFactory->getConfig()->isOrderSearchEnabled();

        if (!$isOrderSearchEnabled) {
            $orderListTransfer = $customerPageFactory->createOrderReader()->getOrderList($request, $orderListTransfer);
            $aggregatedDisplayNames = $this->getFactory()->createItemStateMapper()->aggregateItemStatesDisplayNamesByOrderReference($orderListTransfer->getOrders());

            return [
                'pagination' => $orderListTransfer->getPagination(),
                'orderList' => $orderListTransfer->getOrders(),
                'ordersAggregatedItemStateDisplayNames' => $aggregatedDisplayNames,
                'isOrderSearchEnabled' => $isOrderSearchEnabled,
                'isOrderSearchOrderItemsVisible' => true,
            ];
        }

        $orderSearchForm = $customerPageFactory->createCustomerFormFactory()->getOrderSearchForm();
        $orderListTransfer = $this->handleOrderSearchFormSubmit($request, $orderSearchForm, $orderListTransfer);

        $orderListTransfer = $customerPageFactory->createOrderReader()
            ->getOrderList($request, $orderListTransfer);
        $aggregatedDisplayNames = $this->getFactory()->createItemStateMapper()->aggregateItemStatesDisplayNamesByOrderReference($orderListTransfer->getOrders());

        return [
            'pagination' => $orderListTransfer->getPagination(),
            'orderList' => $orderListTransfer->getOrders(),
            'ordersAggregatedItemStateDisplayNames' => $aggregatedDisplayNames,
            'isOrderSearchEnabled' => $isOrderSearchEnabled,
            'isOrderSearchOrderItemsVisible' => $orderListTransfer->getFormat()->getExpandWithItems(),
            'orderSearchForm' => $orderSearchForm->createView(),
            'filterFields' => $orderListTransfer->getFilterFields()->getArrayCopy(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function detailsAction(Request $request)
    {
        $responseData = $this->getOrderDetailsResponseData($request->query->getInt('id'));

        return $this->view(
            $responseData,
            $this->getFactory()->getCustomerOrderViewWidgetPlugins(),
            '@CustomerPage/views/order-detail/order-detail.twig',
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $orderSearchForm
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function handleOrderSearchFormSubmit(
        Request $request,
        FormInterface $orderSearchForm,
        OrderListTransfer $orderListTransfer
    ): OrderListTransfer {
        /** @var array $data */
        $data = $request->query->all()[OrderSearchForm::FORM_NAME] ?? [];
        $isReset = $data[OrderSearchForm::FIELD_RESET] ?? null;

        if ($isReset) {
            return $this->getFactory()
                ->createOrderSearchFormHandler()
                ->resetFilterFields($orderListTransfer);
        }

        $orderSearchForm->handleRequest($request);

        return $this->getFactory()
            ->createOrderSearchFormHandler()
            ->handleOrderSearchFormSubmit($orderSearchForm, $orderListTransfer);
    }

    /**
     * @param int $idSalesOrder
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array
     */
    protected function getOrderDetailsResponseData(int $idSalesOrder): array
    {
        $customerTransfer = $this->getLoggedInCustomerTransfer();

        $orderTransfer = new OrderTransfer();
        $orderTransfer->setIdSalesOrder($idSalesOrder)
            ->setFkCustomer($customerTransfer->getIdCustomer())
            ->setCustomer($customerTransfer);

        $orderTransfer = $this->getFactory()
            ->getSalesClient()
            ->getOrderDetails($orderTransfer);

        if ($orderTransfer->getIdSalesOrder() === null) {
            throw new NotFoundHttpException(sprintf(
                "Order with provided ID %s doesn't exist",
                $idSalesOrder,
            ));
        }

        $shipmentGroupCollection = $this->getFactory()
            ->getShipmentService()
            ->groupItemsByShipment($orderTransfer->getItems());

        $shipmentGroupCollection = $this->getFactory()
            ->createShipmentGroupExpander()
            ->expandShipmentGroupsWithCartItems($shipmentGroupCollection, $orderTransfer);

        $orderShipmentExpenses = $this->prepareOrderShipmentExpenses($orderTransfer, $shipmentGroupCollection);

        return [
            'order' => $orderTransfer,
            'shipmentGroups' => $shipmentGroupCollection,
            'orderShipmentExpenses' => $orderShipmentExpenses,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param iterable<\Generated\Shared\Transfer\ShipmentGroupTransfer> $shipmentGroupCollection
     *
     * @return iterable<\Generated\Shared\Transfer\ExpenseTransfer>
     */
    protected function prepareOrderShipmentExpenses(
        OrderTransfer $orderTransfer,
        iterable $shipmentGroupCollection
    ): iterable {
        $orderShipmentExpenses = [];

        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            if (
                $expenseTransfer->getType() !== CustomerPageConfig::SHIPMENT_EXPENSE_TYPE
                || $expenseTransfer->getShipment() === null
            ) {
                continue;
            }

            $shipmentHashKey = $this->findShipmentHashKeyByShipmentExpense($shipmentGroupCollection, $expenseTransfer);
            if ($shipmentHashKey === null) {
                $orderShipmentExpenses[] = $expenseTransfer;

                continue;
            }

            $orderShipmentExpenses[$shipmentHashKey] = $expenseTransfer;
        }

        return $orderShipmentExpenses;
    }

    /**
     * @param iterable<\Generated\Shared\Transfer\ShipmentGroupTransfer> $shipmentGroupCollection
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return string|null
     */
    protected function findShipmentHashKeyByShipmentExpense(
        iterable $shipmentGroupCollection,
        ExpenseTransfer $expenseTransfer
    ): ?string {
        foreach ($shipmentGroupCollection as $shipmentGroupTransfer) {
            if ($expenseTransfer->getShipment()->getIdSalesShipment() !== $shipmentGroupTransfer->getShipment()->getIdSalesShipment()) {
                continue;
            }

            return $shipmentGroupTransfer->getHash();
        }

        return null;
    }
}
