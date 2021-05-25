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
    public function isCartCartTotalAjaxLoadEnabled(): bool
    {
        return $this->get(CartPageConstants::ENABLE_CART_TOTAL_LOAD_AJAX, false);
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isCartCartItemsAjaxLoadEnabled(): bool
    {
        return $this->get(CartPageConstants::ENABLE_CART_ITEMS_LOAD_AJAX, false);
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isAjaxLoadCartTotalDisableQuoteValidation(): bool
    {
        return $this->get(CartPageConstants::DISABLE_QUOTE_VALIDATION_CART_TOTAL_LOAD_AJAX, true);
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isAjaxLoadCartItemsDisableQuoteValidation(): bool
    {
        return $this->get(CartPageConstants::DISABLE_QUOTE_VALIDATION_CART_ITEMS_LOAD_AJAX, true);
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
