<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\ActiveSearchFilter;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Symfony\Component\HttpFoundation\Request;

interface UrlGeneratorInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $searchResultTransfer
     * @param string $filterValue
     *
     * @return string
     */
    public function generateUrlWithoutActiveSearchFilter(Request $request, TransferInterface $searchResultTransfer, $filterValue);

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $facetFilters
     *
     * @return string
     */
    public function generateUrlWithoutAllActiveSearchFilters(Request $request, array $facetFilters);
}
