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
     * - Yves public folder for assets.
     * - %store% will be replaced with current store.
     * - %theme% will be replaced with current theme.
     *
     * @api
     */
    public const YVES_PUBLIC_FOLDER_PATH_PATTERN = 'SHOP_UI:YVES_PUBLIC_FOLDER_PATH_PATTERN';
}
