<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AssetWidget\Business;

use Generated\Shared\Transfer\AssetSlotContentRequestTransfer;
use Generated\Shared\Transfer\AssetSlotContentResponseTransfer;
use Generated\Shared\Transfer\AssetStorageCriteriaTransfer;
use SprykerShop\Yves\AssetWidget\Dependency\Client\AssetWidgetToAssetStorageClientInterface;
use SprykerShop\Yves\AssetWidget\Dependency\Client\AssetWidgetToStoreClientInterface;

class AssetWidgetDataProvider implements AssetWidgetDataProviderInterface
{
    /**
     * @var string
     */
    protected static $currentStoreName;

    /**
     * @var \SprykerShop\Yves\AssetWidget\Dependency\Client\AssetWidgetToAssetStorageClientInterface
     */
    protected $assetStorageClient;

    /**
     * @var \SprykerShop\Yves\AssetWidget\Dependency\Client\AssetWidgetToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \SprykerShop\Yves\AssetWidget\Dependency\Client\AssetWidgetToAssetStorageClientInterface $assetStorageClient
     * @param \SprykerShop\Yves\AssetWidget\Dependency\Client\AssetWidgetToStoreClientInterface $storeClient
     */
    public function __construct(
        AssetWidgetToAssetStorageClientInterface $assetStorageClient,
        AssetWidgetToStoreClientInterface $storeClient
    ) {
        $this->assetStorageClient = $assetStorageClient;
        $this->storeClient = $storeClient;
    }

    /**
     * @param \Generated\Shared\Transfer\AssetSlotContentRequestTransfer $assetSlotContentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AssetSlotContentResponseTransfer
     */
    public function getSlotContent(AssetSlotContentRequestTransfer $assetSlotContentRequestTransfer): AssetSlotContentResponseTransfer
    {
        $assetStorageCriteriaTransfer = (new AssetStorageCriteriaTransfer())
            ->setAssetSlot($assetSlotContentRequestTransfer->getAssetSlot())
            ->setStoreName($this->getCurrentStoreName());

        $assetStorageCollectionTransfer = $this->assetStorageClient
            ->getAssetCollection($assetStorageCriteriaTransfer);

        $content = '';
        foreach ($assetStorageCollectionTransfer->getAssetsStorage() as $assetStorageTransfer) {
            $content .= $assetStorageTransfer->getAssetContent();
        }

        return (new AssetSlotContentResponseTransfer())
            ->setContent($content);
    }

    /**
     * @return string
     */
    protected function getCurrentStoreName(): string
    {
        if (!static::$currentStoreName) {
            static::$currentStoreName = $this->storeClient->getCurrentStore()->getNameOrFail();
        }

        return static::$currentStoreName;
    }
}
