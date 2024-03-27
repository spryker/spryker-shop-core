<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationshipPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class MerchantRelationshipPageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Defines parameter name for page number.
     *
     * @api
     *
     * @var string
     */
    public const PARAM_PAGE = 'page';

    /**
     * Specification:
     * - Defines default page number.
     *
     * @api
     *
     * @var int
     */
    public const DEFAULT_PAGE = 1;

    /**
     * Specification:
     * - Defines maximum items per page.
     *
     * @api
     *
     * @var int
     */
    public const DEFAULT_MAX_PER_PAGE = 10;

    /**
     * Specification:
     * - Defines if filtering by merchant is enabled for merchant relationship table.
     *
     * @api
     *
     * @return bool
     */
    public function isFilterByMerchantEnabledForMerchantRelationshipTable(): bool
    {
        return true;
    }
}
