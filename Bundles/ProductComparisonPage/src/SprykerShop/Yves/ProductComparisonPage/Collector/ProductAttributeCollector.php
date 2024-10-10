<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductComparisonPage\Collector;

class ProductAttributeCollector implements ProductAttributeCollectorInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\ProductViewTransfer> $productViewTransfers
     *
     * @return list<string>
     */
    public function collectUniqueProductAttributes(array $productViewTransfers): array
    {
        $productAttributes = [];
        foreach ($productViewTransfers as $productViewTransfer) {
            $productAttributes[] = array_keys($productViewTransfer->getAttributes());
        }

        return array_unique(array_merge(...$productAttributes));
    }
}
