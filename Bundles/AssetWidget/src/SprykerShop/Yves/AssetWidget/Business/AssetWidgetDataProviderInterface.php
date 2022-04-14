<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AssetWidget\Business;

use Generated\Shared\Transfer\AssetSlotContentRequestTransfer;
use Generated\Shared\Transfer\AssetSlotContentResponseTransfer;

interface AssetWidgetDataProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\AssetSlotContentRequestTransfer $assetSlotContentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AssetSlotContentResponseTransfer
     */
    public function getSlotContent(AssetSlotContentRequestTransfer $assetSlotContentRequestTransfer): AssetSlotContentResponseTransfer;
}
