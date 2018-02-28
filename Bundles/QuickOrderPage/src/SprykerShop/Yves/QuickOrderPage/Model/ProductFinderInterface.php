<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Model;

interface ProductFinderInterface
{
    /**
     * @param string $searchString
     * @param string|null $searchField
     * @param int|null $limit
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function getSearchResults(string $searchString, string $searchField = null, int $limit = null): array;
}
