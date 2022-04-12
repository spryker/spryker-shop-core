<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesOrderThresholdWidget\Widget;

use ArrayObject;
use Generated\Shared\Transfer\ExpenseTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use SprykerShop\Yves\SalesOrderThresholdWidget\SalesOrderThresholdWidgetConfig;

/**
 * @method \SprykerShop\Yves\SalesOrderThresholdWidget\SalesOrderThresholdWidgetFactory getFactory()
 */
class SalesOrderThresholdWidget extends AbstractWidget
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ExpenseTransfer> $expenseTransfers
     */
    public function __construct(ArrayObject $expenseTransfers)
    {
        $this->addParameter('expenses', $this->filterSalesOrderThresholdExpenses($expenseTransfers));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'SalesOrderThresholdWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SalesOrderThresholdWidget/views/sales-order-threshold-cart-expenses/sales-order-threshold-cart-expenses.twig';
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ExpenseTransfer> $expenseTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ExpenseTransfer>
     */
    protected function filterSalesOrderThresholdExpenses(ArrayObject $expenseTransfers): ArrayObject
    {
        $filteredResult = array_filter($expenseTransfers->getArrayCopy(), function (ExpenseTransfer $expenseTransfer) {
            return $expenseTransfer->getType() === SalesOrderThresholdWidgetConfig::THRESHOLD_EXPENSE_TYPE;
        });

        return new ArrayObject($filteredResult);
    }
}
