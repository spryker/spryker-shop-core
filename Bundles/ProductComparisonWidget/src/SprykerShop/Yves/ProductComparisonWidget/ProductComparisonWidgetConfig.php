<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductComparisonWidget;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class ProductComparisonWidgetConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const MAX_ITEMS_IN_COMPARE_LIST = 3;

    /**
     * Specification:
     * - Returns the maximum number of items that can be added to the compare list.
     *
     * @api
     *
     * @return int
     */
    public function getMaxItemsInCompareList(): int
    {
        return static::MAX_ITEMS_IN_COMPARE_LIST;
    }
}
