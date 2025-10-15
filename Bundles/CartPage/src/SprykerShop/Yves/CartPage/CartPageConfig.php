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
     * The default value will be true in the next major.
     *
     * @var bool
     */
    protected const IS_LOADING_UPSELLING_PRODUCTS_VIA_AJAX_ENABLED = false;

    /**
     * The default value will be true in the next major.
     *
     * @var bool
     */
    protected const IS_CART_CART_ITEMS_VIA_AJAX_LOAD_ENABLED = false;

    /**
     * @var bool
     */
    protected const IS_CART_ACTIONS_ASYNC_MODE_ENABLED = false;

    /**
     * @var string|null
     */
    protected const CART_BLOCK_MINI_CART_VIEW_TEMPLATE_PATH = null;

    /**
     * @var bool
     */
    protected const IS_QUOTE_VALIDATION_ENABLED_FOR_AJAX_CART_ITEMS = true;

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
        return static::IS_CART_CART_ITEMS_VIA_AJAX_LOAD_ENABLED;
    }

    /**
     * Specification:
     * - Enables loading the upselling products via AJAX.
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

    /**
     * Specification:
     * - Enables performing the quote validation for getting cart items via AJAX.
     *
     * @api
     *
     * @return bool
     */
    public function isQuoteValidationEnabledForAjaxCartItems(): bool
    {
        return static::IS_QUOTE_VALIDATION_ENABLED_FOR_AJAX_CART_ITEMS;
    }

    /**
     * Specification:
     * - Enables performing the cart actions via AJAX.
     *
     * @api
     *
     * @return bool
     */
    public function isCartActionsAsyncModeEnabled(): bool
    {
        return static::IS_CART_ACTIONS_ASYNC_MODE_ENABLED;
    }

    /**
     * Specification:
     * - Returns the template path for the cart block mini cart view.
     *
     * @api
     *
     * @return string|null
     */
    public function getCartBlockMiniCartViewTemplatePath(): ?string
    {
        return static::CART_BLOCK_MINI_CART_VIEW_TEMPLATE_PATH;
    }

    /**
     * Specification:
     * - Returns the cache lifetime for cart validation operations in seconds.
     *
     * @api
     *
     * @return int
     */
    public function getCartValidationCacheTTL(): int
    {
        return 0;
    }
}
