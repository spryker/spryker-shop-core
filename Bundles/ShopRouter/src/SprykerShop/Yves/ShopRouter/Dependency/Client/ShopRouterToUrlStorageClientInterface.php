<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopRouter\Dependency\Client;

/**
 * @deprecated Use `spryker-shop/storage-router` instead.
 */
interface ShopRouterToUrlStorageClientInterface
{
    /**
     * @param string $url
     * @param string $localeName
     *
     * @return array
     */
    public function matchUrl($url, $localeName);
}
