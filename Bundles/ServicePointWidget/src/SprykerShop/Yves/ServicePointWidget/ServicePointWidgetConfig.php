<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointWidget;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class ServicePointWidgetConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const SEARCH_RESULT_LIMIT = 10;

    /**
     * Specification:
     * - Defines number of search results returned in single batch.
     * - Used as a fallback.
     *
     * @api
     *
     * @return int
     */
    public function getSearchResultLimit(): int
    {
        return static::SEARCH_RESULT_LIMIT;
    }
}
