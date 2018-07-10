<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class CatalogPageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Choose, how handel blacklisted category.
     * - If return value is true - category will be shown, but gray out.
     * - If return value is false - category will be hidden from customer.
     *
     * @return bool
     */
    public function isEmptyCategoryFilterValueVisible(): bool
    {
        return true;
    }
}
