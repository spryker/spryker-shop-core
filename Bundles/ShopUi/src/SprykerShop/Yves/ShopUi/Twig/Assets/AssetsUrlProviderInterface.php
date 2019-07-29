<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Twig\Assets;

interface AssetsUrlProviderInterface
{
    /**
     * @return string
     */
    public function getAssetsUrl(): string;
}
