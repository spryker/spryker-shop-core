<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\ViewDataTransformer;

interface ViewDataTransformerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     * @param \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFormColumnPluginInterface[] $additionalColumnPlugins
     *
     * @return array
     */
    public function transformProductData(array $productConcreteTransfers, array $additionalColumnPlugins): array;
}
