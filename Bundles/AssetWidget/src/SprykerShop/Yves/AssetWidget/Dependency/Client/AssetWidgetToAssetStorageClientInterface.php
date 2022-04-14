<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AssetWidget\Dependency\Client;

use Generated\Shared\Transfer\AssetStorageCollectionTransfer;
use Generated\Shared\Transfer\AssetStorageCriteriaTransfer;

interface AssetWidgetToAssetStorageClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\AssetStorageCriteriaTransfer $assetStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AssetStorageCollectionTransfer
     */
    public function getAssetCollection(
        AssetStorageCriteriaTransfer $assetStorageCriteriaTransfer
    ): AssetStorageCollectionTransfer;
}
