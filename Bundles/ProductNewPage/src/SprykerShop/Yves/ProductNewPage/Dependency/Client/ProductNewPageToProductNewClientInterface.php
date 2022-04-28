<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductNewPage\Dependency\Client;

interface ProductNewPageToProductNewClientInterface
{
    /**
     * @param array<string, mixed> $requestParameters
     *
     * @return array
     */
    public function findNewProducts(array $requestParameters = []);
}
