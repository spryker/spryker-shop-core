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
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    public function findLabelsByIdProductAbstract($idProductAbstract, $localeName)
    {
        return $this->productLabelStorageClient->findLabelsByIdProductAbstract($idProductAbstract, $localeName);
    }

    /**
     * @param array $idProductLabels
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    public function findLabels(array $idProductLabels, $localeName)
    {
        return $this->productLabelStorageClient->findLabels($idProductLabels, $localeName);
    }

    /**
     * @param string $labelName
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer|null
     */
    public function findLabelByName($labelName, $localeName)
    {
        return $this->productLabelStorageClient->findLabelByName($labelName, $localeName);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductView(ProductViewTransfer $productViewTransfer, string $locale): ProductViewTransfer
    {
        return $this->productLabelStorageClient->expandProductView($productViewTransfer, $locale);
    }
}
