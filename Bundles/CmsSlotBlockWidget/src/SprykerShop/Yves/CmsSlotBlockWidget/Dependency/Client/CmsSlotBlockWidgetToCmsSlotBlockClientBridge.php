<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsSlotBlockWidget\Dependency\Client;

use Generated\Shared\Transfer\CmsBlockTransfer;

class CmsSlotBlockWidgetToCmsSlotBlockClientBridge implements CmsSlotBlockWidgetToCmsSlotBlockClientInterface
{
    /**
     * @var \Spryker\Client\CmsSlotBlock\CmsSlotBlockClientInterface
     */
    protected $cmsSlotBlockClient;

    /**
     * @param \Spryker\Client\CmsSlotBlock\CmsSlotBlockClientInterface $cmsSlotBlockClient
     */
    public function __construct($cmsSlotBlockClient)
    {
        $this->cmsSlotBlockClient = $cmsSlotBlockClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     * @param array $cmsSlotParams
     *
     * @return bool
     */
    public function isCmsBlockVisibleInSlot(CmsBlockTransfer $cmsBlockTransfer, array $cmsSlotParams): bool
    {
        return $this->cmsSlotBlockClient->isCmsBlockVisibleInSlot($cmsBlockTransfer, $cmsSlotParams);
    }
}
