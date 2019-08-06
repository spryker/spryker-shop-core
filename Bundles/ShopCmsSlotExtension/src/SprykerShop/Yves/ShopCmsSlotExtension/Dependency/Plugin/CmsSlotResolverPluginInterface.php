<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CmsSlotRequestTransfer;

interface CmsSlotResolverPluginInterface
{
    /**
     * Specification:
     * - Resolves a cms slot content by CmsSlotRequestTransfer.
     * - Should return a string which represents CmsSlot in an HTML view.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotRequestTransfer $cmsSlotRequestTransfer
     *
     * @return string
     */
    public function getSlotContent(CmsSlotRequestTransfer $cmsSlotRequestTransfer): string;
}
