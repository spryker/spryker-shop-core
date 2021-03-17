<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistPage\Dependency\Client;

interface WishlistPageToGlossaryStorageClientInterface
{
    /**
     * @param string $id
     * @param string $localeName
     * @param string[] $parameters
     *
     * @return string
     */
    public function translate($id, $localeName, array $parameters = []);
}
