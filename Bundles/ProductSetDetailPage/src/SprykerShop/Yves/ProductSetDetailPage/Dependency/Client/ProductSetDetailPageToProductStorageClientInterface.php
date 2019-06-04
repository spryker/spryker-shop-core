<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSetDetailPage\Dependency\Client;

use Generated\Shared\Transfer\ProductViewTransfer;

interface ProductSetDetailPageToProductStorageClientInterface
{
    /**
     * @param int $idProductAbstract
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer|null
     */
    public function findProductAbstractViewTransfer(int $idProductAbstract, string $localeName, array $selectedAttributes = []): ?ProductViewTransfer;

    /**
     * @param int[] $productAbstractIds
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function getProductAbstractViewTransfers(array $productAbstractIds, string $localeName, array $selectedAttributes = []): array;
}
