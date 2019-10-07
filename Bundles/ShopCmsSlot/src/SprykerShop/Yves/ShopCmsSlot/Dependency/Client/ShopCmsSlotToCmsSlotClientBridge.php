<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopCmsSlot\Dependency\Client;

use Generated\Shared\Transfer\CmsSlotExternalDataTransfer;

class ShopCmsSlotToCmsSlotClientBridge implements ShopCmsSlotToCmsSlotClientInterface
{
    /**
     * @var \Spryker\Client\CmsSlot\CmsSlotClientInterface
     */
    protected $cmsSlotClient;

    /**
     * @param \Spryker\Client\CmsSlot\CmsSlotClientInterface $cmsSlotClient
     */
    public function __construct($cmsSlotClient)
    {
        $this->cmsSlotClient = $cmsSlotClient;
    }

    /**
     * @param string[] $dataKeys
     *
     * @return \Generated\Shared\Transfer\CmsSlotExternalDataTransfer
     */
    public function getCmsSlotExternalDataByKeys(array $dataKeys): CmsSlotExternalDataTransfer
    {
        return $this->cmsSlotClient->getCmsSlotExternalDataByKeys($dataKeys);
    }
}
