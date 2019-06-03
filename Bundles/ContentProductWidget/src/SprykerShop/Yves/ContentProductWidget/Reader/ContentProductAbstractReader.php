<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentProductWidget\Reader;

use SprykerShop\Yves\ContentProductWidget\Dependency\Client\ContentProductWidgetToContentProductClientBridgeInterface;
use SprykerShop\Yves\ContentProductWidget\Dependency\Client\ContentProductWidgetToProductStorageClientBridgeInterface;

class ContentProductAbstractReader implements ContentProductAbstractReaderInterface
{
    /**
     * @var \SprykerShop\Yves\ContentProductWidget\Dependency\Client\ContentProductWidgetToContentProductClientBridgeInterface
     */
    protected $contentProductClient;

    /**
     * @var \SprykerShop\Yves\ContentProductWidget\Dependency\Client\ContentProductWidgetToProductStorageClientBridgeInterface
     */
    protected $productStorageClient;

    /**
     * @param \SprykerShop\Yves\ContentProductWidget\Dependency\Client\ContentProductWidgetToContentProductClientBridgeInterface $contentProductClient
     * @param \SprykerShop\Yves\ContentProductWidget\Dependency\Client\ContentProductWidgetToProductStorageClientBridgeInterface $productStorageClient
     */
    public function __construct(
        ContentProductWidgetToContentProductClientBridgeInterface $contentProductClient,
        ContentProductWidgetToProductStorageClientBridgeInterface $productStorageClient
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
    public function findProductAbstractCollection(int $idContent, string $localeName): ?array
    {
        $contentProductAbstractListTypeTransfer = $this->contentProductClient->executeProductAbstractListTypeById($idContent, $localeName);

        if ($contentProductAbstractListTypeTransfer === null) {
            return null;
        }

        $productAbstractViewCollection = $this
            ->productStorageClient
            ->findProductAbstractViewTransfers($contentProductAbstractListTypeTransfer->getIdProductAbstracts(), $localeName);

        return $productAbstractViewCollection;
    }
}
