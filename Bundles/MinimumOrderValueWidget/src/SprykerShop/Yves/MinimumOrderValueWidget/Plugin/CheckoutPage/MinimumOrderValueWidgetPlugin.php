<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MinimumOrderValueWidget\Plugin\CheckoutPage;

use ArrayObject;
use Generated\Shared\Transfer\ExpenseTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CheckoutPage\Dependency\Plugin\MinimumOrderValueWidget\MinimumOrderValueWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\MinimumOrderValueWidget\MinimumOrderValueWidgetFactory getFactory()
 */
class MinimumOrderValueWidgetPlugin extends AbstractWidgetPlugin implements MinimumOrderValueWidgetPluginInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenseTransfers
     *
     * @return void
     */
    public function initialize(ArrayObject $expenseTransfers): void
    {
        $expenseTransfers = array_filter($expenseTransfers->getArrayCopy(), function (ExpenseTransfer $expenseTransfer) {
            return $expenseTransfer->getType() === 'THRESHOLD_EXPENSE_TYPE';
        });

        $this->addParameter('expenses', $expenseTransfers);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@MinimumOrderValueWidget/views/minimum-order-value-checkout-expenses/minimum-order-value-checkout-expenses.twig';
    }
}
