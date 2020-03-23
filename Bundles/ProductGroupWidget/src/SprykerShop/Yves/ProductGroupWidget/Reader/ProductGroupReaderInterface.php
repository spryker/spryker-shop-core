<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductGroupWidget\Reader;

interface ProductGroupReaderInterface
{
    /**
     * @param int $idProductAbstract
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function getProductGroups(int $idProductAbstract, string $localeName, array $selectedAttributes = []): array;
}
