<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class SalesReturnPageConfig extends AbstractBundleConfig
{
    protected const RETURN_LIST_LIMIT = 10;

    protected const PARAM_PAGE = 'page';
    protected const PARAM_DEFAULT_PAGE = 1;

    /**
     * @api
     *
     * @return int
     */
    public function getReturnListLimit(): int
    {
        return static::RETURN_LIST_LIMIT;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getParamPage(): string
    {
        return static::PARAM_PAGE;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getParamDefaultPage(): int
    {
        return static::PARAM_DEFAULT_PAGE;
    }
}
