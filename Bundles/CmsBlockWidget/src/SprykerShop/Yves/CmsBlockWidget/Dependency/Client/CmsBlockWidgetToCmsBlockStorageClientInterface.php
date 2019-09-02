<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget\Dependency\Client;

interface CmsBlockWidgetToCmsBlockStorageClientInterface
{
    /**
     * @deprecated Use `\SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToCmsBlockStorageClientInterface::findBlocksByNames()` instead.
     *
     * @param string[] $blockNames
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function findBlocksByNames($blockNames, $localeName, $storeName): array;

    /**
     * @param string[] $blockKeys
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function findBlocksByKeys(array $blockKeys, string $localeName, string $storeName): array;

    /**
     * @param array $options
     *
     * @return array
     */
    public function findBlockKeysByOptions(array $options): array;
}
