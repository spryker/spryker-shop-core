<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class CartPageConfig extends AbstractBundleConfig
{
    protected const IS_LOADING_UPSELLING_PRODUCTS_VIA_AJAX_ENABLED = false;

    /**
     * Specification:
     * - Enables loading the upselling products via AJAX.
     * The default value will be true in the next major.
     *
     * @api
     *
     * @return bool
     */
    public function isLoadingUpsellingProductsViaAjaxEnabled(): bool
    {
        return static::IS_LOADING_UPSELLING_PRODUCTS_VIA_AJAX_ENABLED;
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
