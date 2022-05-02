<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\StorageRouter\Dependency\Client;

interface StorageRouterToUrlStorageClientInterface
{
    /**
     * @param string $url
     * @param string|null $localeName
     *
     * @return array<string, mixed>
     */
    public function matchUrl($url, $localeName): array;
}
