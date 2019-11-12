<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsSlotBlockWidget\Dependency\Client;

use Generated\Shared\Transfer\CmsSlotBlockStorageDataTransfer;

interface CmsSlotBlockWidgetToCmsSlotBlockStorageClientInterface
{
    /**
     * @param string $cmsSlotTemplatePath
     * @param string $cmsSlotKey
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockStorageDataTransfer|null
     */
    public function findCmsSlotBlockStorageData(
        string $cmsSlotTemplatePath,
        string $cmsSlotKey
    ): ?CmsSlotBlockStorageDataTransfer;
}
