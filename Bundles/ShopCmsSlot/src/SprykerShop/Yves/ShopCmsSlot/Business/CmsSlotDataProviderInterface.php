<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopCmsSlot\Business;

use Generated\Shared\Transfer\CmsSlotDataTransfer;

interface CmsSlotDataProviderInterface
{
    /**
     * @param string $cmsSlotKey
     * @param array $providedData
     * @param string[] $requiredKeys
     * @param string[] $autoFillingKeys
     *
     * @return \Generated\Shared\Transfer\CmsSlotDataTransfer
     */
    public function getSlotContent(
        string $cmsSlotKey,
        array $providedData,
        array $requiredKeys,
        array $autoFillingKeys
    ): CmsSlotDataTransfer;
}
