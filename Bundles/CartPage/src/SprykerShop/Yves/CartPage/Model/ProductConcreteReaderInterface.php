<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Model;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductConcreteReaderInterface
{
    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcreteTransferBySku(string $sku): ProductConcreteTransfer;
}
