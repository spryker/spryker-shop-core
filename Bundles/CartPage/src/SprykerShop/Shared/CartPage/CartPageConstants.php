<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Shared\CartPage;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface CartPageConstants
{
    /**
     * Specification:
     * - Enables the endpoint to get upselling cart items.
     *
     * @api
     */
    public const ENABLE_CART_UPSELING_LOAD_AJAX = 'CART_PAGE:ENABLE_CART_UPSELING_LOAD_AJAX';

    /**
     * Specification:
     * - Enables the endpoint to get cart items.
     *
     * @api
     */
    public const ENABLE_CART_ITEMS_LOAD_AJAX = 'CART_PAGE:ENABLE_CART_ITEMS_LOAD_AJAX';

    /**
     * Specification:
     * - Disable Quote validation for get cart items via ajax.
     *
     * @api
     */
    public const DISABLE_QUOTE_VALIDATION_CART_ITEMS_LOAD_AJAX = 'CART_PAGE:DISABLE_QUOTE_VALIDATION_CART_ITEMS_LOAD_AJAX';

    /**
     * Specification:
     * - Enables the endpoint to get cart total section.
     *
     * @api
     */
    public const ENABLE_CART_TOTAL_LOAD_AJAX = 'CART_PAGE:ENABLE_CART_TOTAL_LOAD_AJAX';

    /**
     * Specification:
     * - Disable Quote validation for get cart total section via ajax.
     *
     * @api
     */
    public const DISABLE_QUOTE_VALIDATION_CART_TOTAL_LOAD_AJAX = 'CART_PAGE:DISABLE_QUOTE_VALIDATION_CART_TOTAL_LOAD_AJAX';
}
