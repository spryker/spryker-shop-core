<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopCmsSlot\Dependency\Client;

use Generated\Shared\Transfer\CmsSlotTransfer;

class ShopCmsSlotToCmsSlotStorageClientBridge implements ShopCmsSlotToCmsSlotStorageClientInterface
{
    /**
     * @var \Spryker\Client\CmsSlotStorage\CmsSlotStorageClientInterface
     */
    protected $cmsSlotStorageClient;

    /**
     * @param \Spryker\Client\CmsSlotStorage\CmsSlotStorageClientInterface $cmsSlotStorageClient
     */
    public function __construct($cmsSlotStorageClient)
    {
        $this->cmsSlotStorageClient = $cmsSlotStorageClient;
    }

    /**
     * @param string $cmsSlotKey
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer|null
     */
    public function findCmsSlotByKey(string $cmsSlotKey): ?CmsSlotTransfer
    {
        return $this->cmsSlotStorageClient->findCmsSlotByKey($cmsSlotKey);
    }
}
