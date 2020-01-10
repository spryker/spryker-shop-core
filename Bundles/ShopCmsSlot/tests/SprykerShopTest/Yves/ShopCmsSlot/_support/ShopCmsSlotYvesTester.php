<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\ShopCmsSlot;

use Codeception\Actor;
use Generated\Shared\DataBuilder\CmsSlotContentResponseBuilder;
use Generated\Shared\DataBuilder\CmsSlotStorageBuilder;
use Generated\Shared\Transfer\CmsSlotContentResponseTransfer;
use Generated\Shared\Transfer\CmsSlotContextTransfer;
use Generated\Shared\Transfer\CmsSlotStorageTransfer;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class ShopCmsSlotYvesTester extends Actor
{
    use _generated\ShopCmsSlotYvesTesterActions;

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
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CmsSlotStorageTransfer
     */
    public function getCmsSlotStorageTransfer(array $seedData = []): CmsSlotStorageTransfer
    {
        return (new CmsSlotStorageBuilder($seedData))->build();
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
