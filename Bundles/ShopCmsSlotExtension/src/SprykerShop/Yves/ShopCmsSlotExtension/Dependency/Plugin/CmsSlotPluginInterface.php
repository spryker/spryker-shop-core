<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CmsSlotDataTransfer;
use Generated\Shared\Transfer\CmsSlotRequestTransfer;

interface CmsSlotPluginInterface
{
    /**
     * Specification:
     * - Resolves a cms slot content by CmsSlotRequestTransfer.
     * - Should return a CmsSlotDataTransfer which contains data needed for CmsSlot rendering.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotRequestTransfer $cmsSlotRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotDataTransfer
     */
    public function getSlotContent(CmsSlotRequestTransfer $cmsSlotRequestTransfer): CmsSlotDataTransfer;
}
