<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerShop\Shared\CartPage\CartPageConstants;

class CartPageConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return bool
     */
    public function isCartUpsellingAjaxLoadEnabled(): bool
    {
        return $this->get(CartPageConstants::ENABLE_CART_UPSELING_LOAD_AJAX, false);
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isQuoteValidationEnabled(): bool
    {
        return true;
    }
}
