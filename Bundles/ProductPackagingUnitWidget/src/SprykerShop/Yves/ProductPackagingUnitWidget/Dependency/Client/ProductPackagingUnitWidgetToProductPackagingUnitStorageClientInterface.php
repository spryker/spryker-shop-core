<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductPackagingUnitWidget\Dependency\Client;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer;

interface ProductPackagingUnitWidgetToProductPackagingUnitStorageClientInterface
{
    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer|null
     */
    public function findProductConcretePackagingById(int $idProductConcrete): ?ProductConcretePackagingStorageTransfer;

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expandItemTransferWithDefaultPackagingUnit(ItemTransfer $itemTransfer): ItemTransfer;
}
