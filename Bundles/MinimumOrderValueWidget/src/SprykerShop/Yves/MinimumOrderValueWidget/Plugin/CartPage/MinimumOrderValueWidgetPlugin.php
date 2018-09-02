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

/**
 * @method \SprykerShop\Yves\MinimumOrderValueWidget\MinimumOrderValueWidgetFactory getFactory()
 */
class MinimumOrderValueWidgetPlugin extends AbstractWidgetPlugin implements MinimumOrderValueWidgetPluginInterface
{
    /**
     * @see \Spryker\Shared\MinimumOrderValue\MinimumOrderValueConfig::THRESHOLD_EXPENSE_TYPE
     */
    protected const THRESHOLD_EXPENSE_TYPE = 'THRESHOLD_EXPENSE_TYPE';

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenseTransfers
     *
     * @return void
     */
    public function initialize(ArrayObject $expenseTransfers): void
    {
        $expenseTransfers = array_filter($expenseTransfers->getArrayCopy(), function (ExpenseTransfer $expenseTransfer) {
            return $expenseTransfer->getType() === static::THRESHOLD_EXPENSE_TYPE;
        });

        $this->addParameter('expenses', $expenseTransfers);
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
}
