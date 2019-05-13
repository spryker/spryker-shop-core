<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentProductSetWidget\Reader;

use Generated\Shared\Transfer\ProductSetDataStorageTransfer;
use SprykerShop\Yves\ContentProductSetWidget\Dependency\Client\ContentProductSetWidgetToContentProductSetClientInterface;
use SprykerShop\Yves\ContentProductSetWidget\Dependency\Client\ContentProductSetWidgetToProductSetStorageClientInterface;

class ContentProductSetReader implements ContentProductSetReaderInterface
{
    /**
     * @var \SprykerShop\Yves\ContentProductSetWidget\Dependency\Client\ContentProductSetWidgetToContentProductSetClientInterface
     */
    protected $contentProductSetClient;

    /**
     * @var \SprykerShop\Yves\ContentProductSetWidget\Dependency\Client\ContentProductSetWidgetToProductSetStorageClientInterface
     */
    protected $productSetStorageClient;

    /**
     * @param \SprykerShop\Yves\ContentProductSetWidget\Dependency\Client\ContentProductSetWidgetToContentProductSetClientInterface $contentProductSetClient
     * @param \SprykerShop\Yves\ContentProductSetWidget\Dependency\Client\ContentProductSetWidgetToProductSetStorageClientInterface $productSetStorageClient
     */
    public function __construct(
        ContentProductSetWidgetToContentProductSetClientInterface $contentProductSetClient,
        ContentProductSetWidgetToProductSetStorageClientInterface $productSetStorageClient
    ) {
        $this->contentProductSetClient = $contentProductSetClient;
        $this->productSetStorageClient = $productSetStorageClient;
    }

    /**
     * @param int $idContent
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductSetDataStorageTransfer|null
     */
    public function findProductSet(int $idContent, string $localeName): ?ProductSetDataStorageTransfer
    {
        $contentProductSetTypeTransfer = $this->contentProductSetClient->executeProductSetTypeById($idContent, $localeName);

        if ($contentProductSetTypeTransfer === null) {
            return null;
        }

        return $this->productSetStorageClient
            ->getProductSetByIdProductSet($contentProductSetTypeTransfer->getIdProductSet(), $localeName);
    }
}
