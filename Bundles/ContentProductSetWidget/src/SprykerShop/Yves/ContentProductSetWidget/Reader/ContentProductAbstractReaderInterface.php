<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentProductSetWidget\Reader;

use Generated\Shared\Transfer\ProductSetDataStorageTransfer;
use Symfony\Component\HttpFoundation\Request;

interface ContentProductAbstractReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductSetDataStorageTransfer $productSetDataStorageTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]|null
     */
    public function findProductAbstractCollection(
        ProductSetDataStorageTransfer $productSetDataStorageTransfer,
        Request $request,
        string $localeName
    ): ?array;
}
