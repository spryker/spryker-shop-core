<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsSlotBlockWidget\Dependency\Client;

use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Generated\Shared\Transfer\CmsSlotParamsTransfer;

interface CmsSlotBlockWidgetToCmsSlotBlockClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer $cmsSlotBlockTransfer
     * @param \Generated\Shared\Transfer\CmsSlotParamsTransfer $cmsSlotParamsTransfer
     *
     * @return bool
     */
    public function isCmsBlockVisibleInSlot(
        CmsSlotBlockTransfer $cmsSlotBlockTransfer,
        CmsSlotParamsTransfer $cmsSlotParamsTransfer
    ): bool;
}
