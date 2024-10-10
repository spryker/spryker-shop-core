<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductComparisonPage\Reader;

interface ProductComparisonListReaderInterface
{
    /**
     * @param list<string> $skus
     * @param string $localeName
     *
     * @return list<\Generated\Shared\Transfer\ProductViewTransfer>
     */
    public function getProductsCompareList(array $skus, string $localeName): array;
}
