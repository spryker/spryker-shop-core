<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget\Dependency\Client;

interface CmsBlockWidgetToCmsBlockStorageClientInterface
{
    /**
     * @param array $options
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function getCmsBlocksByOptions(array $options, string $localeName, string $storeName): array;
}
