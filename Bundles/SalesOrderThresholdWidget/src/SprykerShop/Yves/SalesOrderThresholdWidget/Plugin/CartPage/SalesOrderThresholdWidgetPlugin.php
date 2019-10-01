<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesOrderThresholdWidget\Plugin\CartPage;

use ArrayObject;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CartPage\Dependency\Plugin\SalesOrderThresholdWidget\SalesOrderThresholdWidgetPluginInterface;
use SprykerShop\Yves\SalesOrderThresholdWidget\Widget\SalesOrderThresholdWidget;

/**
 * @deprecated Use \SprykerShop\Yves\SalesOrderThresholdWidget\Widget\SalesOrderThresholdWidget instead.
 *
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
        $widget = new SalesOrderThresholdWidget($expenseTransfers);

        $this->parameters = $widget->getParameters();
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
        return SalesOrderThresholdWidget::getTemplate();
    }
}
