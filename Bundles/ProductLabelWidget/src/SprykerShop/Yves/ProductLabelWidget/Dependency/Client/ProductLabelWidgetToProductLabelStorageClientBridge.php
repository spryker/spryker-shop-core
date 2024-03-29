<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductLabelWidget\Dependency\Client;

use Generated\Shared\Transfer\ProductViewTransfer;

class ProductLabelWidgetToProductLabelStorageClientBridge implements ProductLabelWidgetToProductLabelStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductLabelStorage\ProductLabelStorageClientInterface
     */
    protected $productLabelStorageClient;

    /**
     * @param \Spryker\Client\ProductLabelStorage\ProductLabelStorageClientInterface $productLabelStorageClient
     */
    public function __construct($productLabelStorageClient)
    {
        $this->productLabelStorageClient = $productLabelStorageClient;
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer>
     */
    public function findLabelsByIdProductAbstract($idProductAbstract, $localeName, string $storeName)
    {
        return $this->productLabelStorageClient->findLabelsByIdProductAbstract($idProductAbstract, $localeName, $storeName);
    }

    /**
     * @param array $idProductLabels
     * @param string $localeName
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer>
     */
    public function findLabels(array $idProductLabels, $localeName, string $storeName)
    {
        return $this->productLabelStorageClient->findLabels($idProductLabels, $localeName, $storeName);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductView(
        ProductViewTransfer $productViewTransfer,
        string $localeName,
        string $storeName
    ): ProductViewTransfer {
        return $this->productLabelStorageClient->expandProductView($productViewTransfer, $localeName, $storeName);
    }
}
