<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\ProductResolver;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductResolverInterface
{
    /**
     * @param string $sku
     *
     * @return int|null
     */
    public function getIdProductBySku(string $sku): ?int;

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductBySku(string $sku): ProductConcreteTransfer;

    /**
     * @param int $idProduct
     *
     * @return int
     */
    public function getIdProductAbstractByIdProduct(int $idProduct): int;

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expandItemTransferWithProductIds(ItemTransfer $itemTransfer): ItemTransfer;
}
