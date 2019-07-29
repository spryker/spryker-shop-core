<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Shared\ShopUi;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface ShopUiConstants
{
    /**
     * Specification:
     * - Returns the path pattern to be used to build the path for Yves assets.
     * - When the path pattern contains the placeholder %theme% it will be replaced with the current theme name e.g. default.
     *
     * @example '/assets/%theme%/'
     *
     * @api
     */
    public const YVES_ASSETS_URL_PATTERN = 'SHOP_UI:YVES_ASSETS_URL_PATTERN';
}
