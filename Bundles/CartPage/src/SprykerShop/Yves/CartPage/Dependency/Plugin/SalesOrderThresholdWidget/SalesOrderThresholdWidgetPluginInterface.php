<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Dependency\Plugin\SalesOrderThresholdWidget;

use ArrayObject;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

/**
 * @deprecated Use {@link \SprykerShop\Yves\SalesOrderThresholdWidget\Widget\SalesOrderThresholdWidget} instead.
 */
interface SalesOrderThresholdWidgetPluginInterface extends WidgetPluginInterface
{
    /**
     * @var string
     */
    public const NAME = 'SalesOrderThresholdWidgetPlugin';

    /**
     * @api
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ExpenseTransfer> $expenseTransfers
     *
     * @return void
     */
    public function initialize(ArrayObject $expenseTransfers): void;
}
