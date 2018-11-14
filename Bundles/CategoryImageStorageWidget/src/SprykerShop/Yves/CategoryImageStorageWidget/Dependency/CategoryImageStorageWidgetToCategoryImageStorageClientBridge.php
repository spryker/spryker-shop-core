<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CategoryImageStorageWidget\Dependency;

use Generated\Shared\Transfer\CategoryImageSetCollectionStorageTransfer;

class CategoryImageStorageWidgetToCategoryImageStorageClientBridge implements CategoryImageStorageWidgetToCategoryImageStorageClientInterface
{
    /**
     * @var \Spryker\Client\CategoryImageStorage\CategoryImageStorageClientInterface
     */
    private $storageClient;

    /**
     * @param \Spryker\Client\CategoryImageStorage\CategoryImageStorageClientInterface $storageClient
     */
    public function __construct($storageClient)
    {
        $this->storageClient = $storageClient;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function getCategoryImageSetCollectionStorage(int $categoryId, string $localeName): ?CategoryImageSetCollectionStorageTransfer
    {
        return $this->storageClient->getCategoryImageSetCollectionStorage($categoryId, $localeName);
    }
}
