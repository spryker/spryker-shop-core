<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerShop\Shared\ShopUi\ShopUiConstants;

class ShopUiConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Yves public folder for assets.
     * - %store% will be replaced with current store.
     * - %theme% will be replaced with current theme.
     *
     * @api
     *
     * @return string
     */
    public function getYvesPublicFolderPathPattern(): string
    {
        return $this->get(ShopUiConstants::YVES_PUBLIC_FOLDER_PATH_PATTERN, '/assets/');
    }
}
