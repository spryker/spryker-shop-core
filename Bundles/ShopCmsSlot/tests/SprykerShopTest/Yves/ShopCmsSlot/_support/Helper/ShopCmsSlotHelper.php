<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\ShopCmsSlot\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CmsSlotContentResponseBuilder;
use Generated\Shared\Transfer\CmsSlotContentResponseTransfer;
use Generated\Shared\Transfer\CmsSlotContextTransfer;

class ShopCmsSlotHelper extends Module
{
    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CmsSlotContentResponseTransfer
     */
    public function getCmsSlotContentResponseTransfer(array $seedData = []): CmsSlotContentResponseTransfer
    {
        return (new CmsSlotContentResponseBuilder($seedData))->build();
    }

    /**
     * @param string $cmsSlotKey
     * @param array $providedData
     * @param string[] $requiredKeys
     * @param string[] $autoFilledKeys
     *
     * @return \Generated\Shared\Transfer\CmsSlotContextTransfer
     */
    public function getCmsSlotContextTransfer(
        string $cmsSlotKey,
        array $providedData,
        array $requiredKeys,
        array $autoFilledKeys
    ): CmsSlotContextTransfer {
        return (new CmsSlotContextTransfer())
            ->setCmsSlotKey($cmsSlotKey)
            ->setProvidedData($providedData)
            ->setRequiredKeys($requiredKeys)
            ->setAutoFilledKeys($autoFilledKeys);
    }
}
