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
     * #GEEGA - Upd description
     * Specification:
     * - Enables the endpoint to show debugging info for the cart.
     *
     * @api
     */
    public const ENABLE_CART_UPSELING_LOAD_AJAX = 'CART_PAGE:ENABLE_CART_UPSELING_LOAD_AJAX';

    /**
     * #GEEGA - Upd description
     * Specification:
     * - Enables the endpoint to show debugging info for the cart.
     *
     * @api
     */
    public const ENABLE_CART_ITEMS_LOAD_AJAX = 'CART_PAGE:ENABLE_CART_ITEMS_LOAD_AJAX';

    /**
     * #GEEGA - Upd description
     * Specification:
     * - Enables the endpoint to show debugging info for the cart.
     *
     * @api
     */
    public const DISABLE_QUOTE_VALIDATION_CART_ITEMS_LOAD_AJAX = 'CART_PAGE:DISABLE_QUOTE_VALIDATION_CART_ITEMS_LOAD_AJAX';

    /**
     * #GEEGA - Upd description
     * Specification:
     * - Enables the endpoint to show debugging info for the cart.
     *
     * @api
     */
    public const ENABLE_CART_TOTAL_LOAD_AJAX = 'CART_PAGE:ENABLE_CART_TOTAL_LOAD_AJAX';

    /**
     * #GEEGA - Upd description
     * Specification:
     * - Enables the endpoint to show debugging info for the cart.
     *
     * @api
     */
    public const DISABLE_QUOTE_VALIDATION_CART_TOTAL_LOAD_AJAX = 'CART_PAGE:DISABLE_QUOTE_VALIDATION_CART_TOTAL_LOAD_AJAX';
}
