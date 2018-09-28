<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Dependency\Client;

use Generated\Shared\Transfer\CurrentProductConcretePriceTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

interface QuickOrderPageToQuickOrderClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expandProductConcreteTransfer(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer;

    /**
     * @param \Generated\Shared\Transfer\CurrentProductConcretePriceTransfer $currentProductConcretePriceTransfer
     *
     * @return \Generated\Shared\Transfer\CurrentProductConcretePriceTransfer
     */
    public function getProductConcreteSumPrice(CurrentProductConcretePriceTransfer $currentProductConcretePriceTransfer): CurrentProductConcretePriceTransfer;
}
