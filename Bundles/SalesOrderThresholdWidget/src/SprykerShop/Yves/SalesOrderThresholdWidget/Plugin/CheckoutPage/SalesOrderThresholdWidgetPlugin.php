<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesOrderThresholdWidget\Plugin\CheckoutPage;

use ArrayObject;
use Generated\Shared\Transfer\ExpenseTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CheckoutPage\Dependency\Plugin\SalesOrderThresholdWidget\SalesOrderThresholdWidgetPluginInterface;
use SprykerShop\Yves\SalesOrderThresholdWidget\SalesOrderThresholdWidgetConfig;

/**
 * @method \SprykerShop\Yves\SalesOrderThresholdWidget\SalesOrderThresholdWidgetFactory getFactory()
 */
class SalesOrderThresholdWidgetPlugin extends AbstractWidgetPlugin implements SalesOrderThresholdWidgetPluginInterface
{
    /**
     * @api
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenseTransfers
     *
     * @return void
     */
    public function initialize(ArrayObject $expenseTransfers): void
    {
        $this->addParameter('expenses', $this->filterSalesOrderThresholdExpenses($expenseTransfers));
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SalesOrderThresholdWidget/views/sales-order-threshold-checkout-expenses/sales-order-threshold-checkout-expenses.twig';
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenseTransfers
     *
     * @return \ArrayObject
     */
    protected function filterSalesOrderThresholdExpenses(ArrayObject $expenseTransfers): ArrayObject
    {
        $filteredResult = array_filter($expenseTransfers->getArrayCopy(), function (ExpenseTransfer $expenseTransfer) {
            return $expenseTransfer->getType() === SalesOrderThresholdWidgetConfig::THRESHOLD_EXPENSE_TYPE;
        });

        return new ArrayObject($filteredResult);
    }
}
