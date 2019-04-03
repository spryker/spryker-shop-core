<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentProductAbstractWidget\Reader;

use SprykerShop\Yves\ContentProductAbstractWidget\Dependency\Client\ContentProductAbstractWidgetToContentProductClientBridgeInterface;
use SprykerShop\Yves\ContentProductAbstractWidget\Dependency\Client\ContentProductAbstractWidgetToProductStorageClientBridgeInterface;

class ContentProductAbstractReader implements ContentProductAbstractReaderInterface
{
    /**
     * @var \SprykerShop\Yves\ContentProductAbstractWidget\Dependency\Client\ContentProductAbstractWidgetToContentProductClientBridgeInterface
     */
    protected $contentProductClient;

    /**
     * @var \SprykerShop\Yves\ContentProductAbstractWidget\Dependency\Client\ContentProductAbstractWidgetToProductStorageClientBridgeInterface
     */
    protected $productStorageClient;

    /**
     * @param \SprykerShop\Yves\ContentProductAbstractWidget\Dependency\Client\ContentProductAbstractWidgetToContentProductClientBridgeInterface $contentProductClient
     * @param \SprykerShop\Yves\ContentProductAbstractWidget\Dependency\Client\ContentProductAbstractWidgetToProductStorageClientBridgeInterface $productStorageClient
     */
    public function __construct(
        ContentProductAbstractWidgetToContentProductClientBridgeInterface $contentProductClient,
        ContentProductAbstractWidgetToProductStorageClientBridgeInterface $productStorageClient
    ) {
        $this->contentProductClient = $contentProductClient;
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param int $idContent
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]|null
     */
    public function getProductAbstractCollection(int $idContent, string $localeName): ?array
    {
        $productAbstractViewCollection = [];
        $contentProductAbstractListTypeTransfer = $this->contentProductClient->findContentProductAbstractListType($idContent, $localeName);

        if ($contentProductAbstractListTypeTransfer === null) {
            return null;
        }

        $productAbstractCollection = $this->productStorageClient->getProductAbstractCollection($contentProductAbstractListTypeTransfer->getIdProductAbstracts(), $localeName);

        foreach ($productAbstractCollection as $productAbstract) {
            $productAbstractViewCollection[] = $this->productStorageClient->mapProductAbstractStorageData($productAbstract, $localeName);
        }

        return $productAbstractViewCollection;
    }
}
