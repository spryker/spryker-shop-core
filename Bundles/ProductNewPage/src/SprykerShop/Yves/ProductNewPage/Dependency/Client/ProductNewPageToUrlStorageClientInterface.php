<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductNewPage\Dependency\Client;

interface ProductNewPageToUrlStorageClientInterface
{
    /**
     * @param string $url
     * @param string $localeName
     *
     * @return array<string, mixed>
     */
    public function matchUrl($url, $localeName);
}
