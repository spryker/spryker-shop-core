<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CmsSlotDataTransfer;
use Generated\Shared\Transfer\CmsSlotContentRequestTransfer;

interface CmsSlotContentPluginInterface
{
    /**
     * Specification:
     * - Resolves a CMS slot content by CmsSlotContentRequestTransfer.
     * - Returns a CmsSlotDataTransfer with data for CmsSlot rendering.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotDataTransfer
     */
    public function getSlotContent(CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer): CmsSlotDataTransfer;
}
