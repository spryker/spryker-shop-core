<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductQuantityRestrictionWidget\Dependency\Service;

interface ProductQuantityRestrictionWidgetToProductQuantityServiceInterface
{
    /**
     * @return float
     */
    public function getDefaultMinimumQuantity(): float;

    /**
     * @return float
     */
    public function getDefaultInterval(): float;
}
