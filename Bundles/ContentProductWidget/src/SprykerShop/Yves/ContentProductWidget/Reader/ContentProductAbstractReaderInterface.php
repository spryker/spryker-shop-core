<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentProductWidget\Reader;

interface ContentProductAbstractReaderInterface
{
    /**
     * @param string $contentKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]|null
     */
    public function findProductAbstractCollection(string $contentKey, string $localeName): ?array;
}
