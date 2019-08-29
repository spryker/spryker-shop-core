<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\ShopCmsSlot\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CmsSlotDataBuilder;
use Generated\Shared\Transfer\CmsSlotContextTransfer;
use Generated\Shared\Transfer\CmsSlotDataTransfer;

class ShopCmsSlotHelper extends Module
{
    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CmsSlotDataTransfer
     */
    public function getCmsSlotDataTransfer(array $seedData = []): CmsSlotDataTransfer
    {
        return (new CmsSlotDataBuilder($seedData))->build();
    }

    /**
     * @param string $cmsSlotKey
     * @param array $providedData
     * @param string[] $requiredKeys
     * @param string[] $autoFillingKeys
     *
     * @return \Generated\Shared\Transfer\CmsSlotContextTransfer
     */
    public function getCmsSlotContextTransfer(
        string $cmsSlotKey,
        array $providedData,
        array $requiredKeys,
        array $autoFillingKeys
    ): CmsSlotContextTransfer {
        return (new CmsSlotContextTransfer())
            ->setCmsSlotKey($cmsSlotKey)
            ->setProvidedData($providedData)
            ->setRequiredKeys($requiredKeys)
            ->setAutoFillingKeys($autoFillingKeys);
    }
}
