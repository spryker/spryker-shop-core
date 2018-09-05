<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MinimumOrderValueWidget\Plugin\CartPage;

use ArrayObject;
use Generated\Shared\Transfer\ExpenseTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CartPage\Dependency\Plugin\MinimumOrderValueWidget\MinimumOrderValueWidgetPluginInterface;
use SprykerShop\Yves\MinimumOrderValueWidget\MinimumOrderValueWidgetConfig;

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
        $this->addParameter('expenses', $this->filterMinimumOrderValueExpenses($expenseTransfers));
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@MinimumOrderValueWidget/views/minimum-order-value-cart-expenses/minimum-order-value-cart-expenses.twig';
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenseTransfers
     *
     * @return \ArrayObject
     */
    protected function filterMinimumOrderValueExpenses(ArrayObject $expenseTransfers): ArrayObject
    {
        $filteredResult = array_filter($expenseTransfers->getArrayCopy(), function (ExpenseTransfer $expenseTransfer) {
            return $expenseTransfer->getType() === MinimumOrderValueWidgetConfig::THRESHOLD_EXPENSE_TYPE;
        });

        return new ArrayObject($filteredResult);
    }
}
