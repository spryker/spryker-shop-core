<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Plugin\CustomerPage;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Symfony\Component\Form\FormView;

/**
 * @method \SprykerShop\Yves\CustomerReorderWidget\CustomerReorderWidgetFactory getFactory()
 */
class CustomerReorderItemsFormWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_FORM = 'form';

    /**
     * @var string
     */
    protected const PARAMETER_ORDER = 'order';

    /**
     * @var string
     */
    protected const PARAMETER_CONFIG = 'config';

    /**
     * @var string
     */
    protected const PARAMETER_ORDER_SHIPMENT_EXPENSES = 'orderShipmentExpenses';

    /**
     * @var string
     */
    protected const PARAMETER_SHIPMENT_GROUPS = 'shipmentGroups';

    /**
     * @var \Symfony\Component\Form\FormView|null
     */
    protected static $customerReorderItemsWidgetFormView;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array<mixed> $config
     * @param array<\Generated\Shared\Transfer\ExpenseTransfer> $orderShipmentExpenses
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ShipmentGroupTransfer>|null $shipmentGroups
     */
    public function __construct(
        OrderTransfer $orderTransfer,
        array $config = [],
        array $orderShipmentExpenses = [],
        ?ArrayObject $shipmentGroups = null
    ) {
        $this->addFormParameter();
        $this->addOrderParameter($orderTransfer);
        $this->addConfigParameter($config);
        $this->addOrderShipmentExpensesParameter($orderShipmentExpenses);
        $this->addShipmentGroupsParameter($shipmentGroups);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CustomerReorderItemsFormWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CustomerReorderWidget/views/customer-reorder-items-form/customer-reorder-items-form.twig';
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    public function createCustomerReorderItemsWidgetFormView(): FormView
    {
        return $this->getFactory()
            ->createCustomerReorderWidgetFormFactory()
            ->getCustomerReorderItemsWidgetForm()
            ->createView();
    }

    /**
     * @return void
     */
    protected function addFormParameter(): void
    {
        $this->addParameter(static::PARAMETER_FORM, $this->getOrCreateCustomerReorderItemsWidgetFormView());
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function addOrderParameter(OrderTransfer $orderTransfer): void
    {
        $this->addParameter(static::PARAMETER_ORDER, $orderTransfer);
    }

    /**
     * @param array<mixed> $config
     *
     * @return void
     */
    protected function addConfigParameter(array $config = []): void
    {
        $this->addParameter(static::PARAMETER_CONFIG, $config);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ExpenseTransfer> $orderShipmentExpenses
     *
     * @return void
     */
    protected function addOrderShipmentExpensesParameter(array $orderShipmentExpenses = []): void
    {
        $this->addParameter(static::PARAMETER_ORDER_SHIPMENT_EXPENSES, $orderShipmentExpenses);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ShipmentGroupTransfer>|null $shipmentGroups
     *
     * @return void
     */
    protected function addShipmentGroupsParameter(?ArrayObject $shipmentGroups = null): void
    {
        $this->addParameter(static::PARAMETER_SHIPMENT_GROUPS, $shipmentGroups);
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    protected function getOrCreateCustomerReorderItemsWidgetFormView(): FormView
    {
        if (static::$customerReorderItemsWidgetFormView === null) {
            static::$customerReorderItemsWidgetFormView = $this->createCustomerReorderItemsWidgetFormView();
        }

        return static::$customerReorderItemsWidgetFormView;
    }
}
