<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CalculationPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerShop\Shared\CalculationPage\CalculationPageConstants;

class CalculationPageConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isCartDebugEnabled(): bool
    {
        return $this->get(CalculationPageConstants::ENABLE_CART_DEBUG, $this->getCartDebugDefaultValue());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return bool
     */
    protected function getCartDebugDefaultValue(): bool
    {
        return APPLICATION_ENV === 'development';
    }
}
