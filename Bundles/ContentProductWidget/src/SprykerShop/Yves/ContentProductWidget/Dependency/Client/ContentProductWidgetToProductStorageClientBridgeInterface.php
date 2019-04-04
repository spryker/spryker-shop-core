<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentProductWidget\Dependency\Client;

use Generated\Shared\Transfer\ProductViewTransfer;

interface ContentProductWidgetToProductStorageClientBridgeInterface
{
    /**
     * @param int[] $idProductAbstracts
     * @param string $localeName
     *
     * @return array
     */
    public function getProductAbstractCollection(array $idProductAbstracts, string $localeName): array;

    /**
     * @param array $data
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function mapProductAbstractStorageData(array $data, string $localeName, array $selectedAttributes = []): ProductViewTransfer;
}
