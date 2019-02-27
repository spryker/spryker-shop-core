<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Dependency\Service;

interface QuickOrderPageToQuickOrderServiceInterface
{
    /**
     * @param float $value
     *
     * @return float
     */
    public function round(float $value): float;
}
