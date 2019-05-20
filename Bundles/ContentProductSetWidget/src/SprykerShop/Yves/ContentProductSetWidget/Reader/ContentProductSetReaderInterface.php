<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentProductSetWidget\Reader;

use Generated\Shared\Transfer\ProductSetDataStorageTransfer;

interface ContentProductSetReaderInterface
{
    /**
     * @param int $idContent
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductSetDataStorageTransfer[]|null
     */
    public function findProductSet(int $idContent, string $localeName): ?ProductSetDataStorageTransfer;
}
