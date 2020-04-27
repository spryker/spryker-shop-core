<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class SalesReturnPageConfig extends AbstractBundleConfig
{
    public const PARAM_DEFAULT_PAGE = 1;

    protected const DEFAULT_RETURN_LIST_PER_PAGE = 10;

    /**
     * @api
     *
     * @return int
     */
    public function getReturnListPerPage(): int
    {
        return static::DEFAULT_RETURN_LIST_PER_PAGE;
    }
}
