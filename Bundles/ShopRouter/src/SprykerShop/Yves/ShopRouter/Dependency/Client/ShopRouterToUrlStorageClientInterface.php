<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopRouter\Dependency\Client;

interface ShopRouterToUrlStorageClientInterface
{
    /**
     * @param string $url
     * @param string $localeName
     *
     * @return array|bool
     */
    public function matchUrl($url, $localeName);
}
