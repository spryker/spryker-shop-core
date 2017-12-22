<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget\Dependency\Client;

interface CmsBlockWidgetToCmsBlockStorageClientInterface
{

    /**
     * @param string[] $blockNames
     * @param string $localeName
     *
     * @return array
     */
    public function findBlocksByNames($blockNames, $localeName);

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
}
