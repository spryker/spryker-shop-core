<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class CartPageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Enables loading the cart items via AJAX.
     *
     * @api
     *
     * @return bool
     */
    public function isCartCartItemsViaAjaxLoadEnabled(): bool
    {
        return true;
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
