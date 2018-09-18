<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class ProductSearchWidgetConfig extends AbstractBundleConfig
{
    protected const SEARCH_RESULTS_DEFAULT_LIMIT = 10;

    /**
     * @return int
     */
    public function getSearchResultsDefaultLimit(): int
    {
        return static::SEARCH_RESULTS_DEFAULT_LIMIT;
    }
}
