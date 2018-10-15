<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Plugin\QuickOrder;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFormColumnPluginInterface;

class QuickOrderFormMeasurementUnitColumnPlugin extends AbstractPlugin implements QuickOrderFormColumnPluginInterface
{
    protected const COLUMN_TITLE = 'quick-order.input-label.measurement_unit';
    protected const FIELD_NAME = 'measurementUnit';

    /**
     * @return string
     */
    public function getColumnTitle(): string
    {
        return static::COLUMN_TITLE;
    }

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return static::FIELD_NAME;
    }
}
