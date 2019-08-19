<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget\Dependency\Client;

interface CmsBlockWidgetToCmsBlockStorageClientInterface
{
    /**
     * @param string[] $blockNames
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function findBlocksByNames($blockNames, $localeName, $storeName);

    /**
     * @param array $options
     * @param string $localName
     *
     * @return array
     */
    public function findBlockNamesByOptions(array $options, $localName);

    /**
     * @param string $name
     *
     * @return string
     */
    public function generateBlockNameKey($name);

    /**
     * @param string[] $blockKeys
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function findBlocksByKeys(array $blockKeys, string $localeName, string $storeName): array;

    /**
     * @param string $blockName
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function findMappingDataByBlockName(string $blockName, string $localeName, string $storeName): array;

    /**
     * @param array $options
     * @param string $localeName
     *
     * @return array
     */
    public function findBlockKeysByOptions(array $options, string $localeName): array;

    /**
     * @return bool
     */
    public function isUseKeyInCmsBlockSearch(): bool;
}
