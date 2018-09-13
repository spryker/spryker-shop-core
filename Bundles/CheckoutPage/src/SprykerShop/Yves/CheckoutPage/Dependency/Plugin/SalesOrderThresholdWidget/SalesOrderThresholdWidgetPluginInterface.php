<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Dependency\Plugin\SalesOrderThresholdWidget;

use ArrayObject;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface SalesOrderThresholdWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'SalesOrderThresholdWidgetPlugin';

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenseTransfers
     *
     * @return void
     */
    public function initialize(ArrayObject $expenseTransfers): void;
}
