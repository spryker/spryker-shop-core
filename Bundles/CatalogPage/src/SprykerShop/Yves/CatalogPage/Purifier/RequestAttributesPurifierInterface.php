<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Purifier;

interface RequestAttributesPurifierInterface
{
    /**
     * @param array $requestAttributes
     *
     * @return array
     */
    public function purifyRequestAttributes(array $requestAttributes): array;
}
