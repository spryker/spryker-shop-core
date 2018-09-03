<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MinimumOrderValueWidget\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\ExpenseTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Shared\MinimumOrderValueWidget\MinimumOrderValueWidgetConfig;

abstract class AbstractMinimumOrderValueWidgetPlugin extends AbstractWidgetPlugin
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenseTransfers
     *
     * @return \ArrayObject
     */
    protected function filterMinimumOrderValueExpenses(ArrayObject $expenseTransfers): ArrayObject
    {
        return array_filter($expenseTransfers->getArrayCopy(), function (ExpenseTransfer $expenseTransfer) {
            return $expenseTransfer->getType() === MinimumOrderValueWidgetConfig::THRESHOLD_EXPENSE_TYPE;
        });
    }
}
