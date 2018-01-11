<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductLabelWidget\Dependency\Client;

interface ProductLabelWidgetToProductLabelStorageClientInterface
{
    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    public function findLabelsByIdProductAbstract($idProductAbstract, $localeName);

    /**
     * @param array $idProductLabels
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    public function findLabels(array $idProductLabels, $localeName);

    /**
     * @param string $labelName
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer|null
     */
    public function findLabelByName($labelName, $localeName);
}