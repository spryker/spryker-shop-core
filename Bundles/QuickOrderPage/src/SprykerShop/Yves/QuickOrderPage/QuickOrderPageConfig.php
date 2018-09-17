<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerShop\Shared\QuickOrderPage\QuickOrderPageConstants;

class QuickOrderPageConfig extends AbstractBundleConfig
{
    /**
     * Deprecated: Do not use ENV config here.
     *
     * @return array
     */
    public function getAllowedSeparators(): array
    {
        return $this->get(QuickOrderPageConstants::ALLOWED_SEPARATORS, [',', ';', ' ']);
    }

    /**
     * Deprecated: Do not use ENV config here.
     *
     * @return int
     */
    public function getProductRowsNumber(): int
    {
        return $this->get(QuickOrderPageConstants::PRODUCT_ROWS_NUMBER, 8);
    }
}
