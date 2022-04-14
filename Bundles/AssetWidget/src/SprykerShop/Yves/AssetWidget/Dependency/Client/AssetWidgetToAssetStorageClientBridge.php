<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AssetWidget\Dependency\Client;

use Generated\Shared\Transfer\AssetStorageCollectionTransfer;
use Generated\Shared\Transfer\AssetStorageCriteriaTransfer;

class AssetWidgetToAssetStorageClientBridge implements AssetWidgetToAssetStorageClientInterface
{
    /**
     * @var \Spryker\Client\AssetStorage\AssetStorageClientInterface
     */
    private $assetStorageClient;

    /**
     * @param \Spryker\Client\AssetStorage\AssetStorageClientInterface $assetStorageClient
     */
    public function __construct($assetStorageClient)
    {
        $this->assetStorageClient = $assetStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\AssetStorageCriteriaTransfer $assetStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AssetStorageCollectionTransfer
     */
    public function getAssetCollection(
        AssetStorageCriteriaTransfer $assetStorageCriteriaTransfer
    ): AssetStorageCollectionTransfer {
        return $this->assetStorageClient
            ->getAssetCollection($assetStorageCriteriaTransfer);
    }
}
