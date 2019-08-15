<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopCmsSlot\Dependency\Client;

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
     * @param string[] $fillingKeys
     *
     * @return array
     */
    public function fetchCmsSlotAutoFilled(array $fillingKeys): array
    {
        return $this->cmsSlotClient->fetchCmsSlotAutoFilled($fillingKeys);
    }
}
