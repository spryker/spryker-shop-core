<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Expander;

use Generated\Shared\Transfer\ProductImageSetStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToProductImageStorageClientInterface;

class ProductConcreteImageExpander implements ProductConcreteImageExpanderInterface
{
    /**
     * @var \SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToProductImageStorageClientInterface
     */
    protected $productImageStorageClient;

    /**
     * @param \SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToProductImageStorageClientInterface $productImageStorageClient
     */
    public function __construct(ConfigurableBundlePageToProductImageStorageClientInterface $productImageStorageClient)
    {
        $this->productImageStorageClient = $productImageStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransferWithImages(ProductViewTransfer $productViewTransfer, string $localeName): ProductViewTransfer
    {
        $productConcreteImageStorageTransfer = $this->productImageStorageClient->findProductImageConcreteStorageTransfer(
            $productViewTransfer->getIdProductConcrete(),
            $localeName
        );

        if (!$productConcreteImageStorageTransfer) {
            return $productViewTransfer;
        }

        foreach ($productConcreteImageStorageTransfer->getImageSets() as $productImageSetStorageTransfer) {
            $productViewTransfer = $this->addImagesFromProductImageSetStorageTransferToProductViewTransfer(
                $productViewTransfer,
                $productImageSetStorageTransfer
            );
        }

        return $productViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\ProductImageSetStorageTransfer $productImageSetStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function addImagesFromProductImageSetStorageTransferToProductViewTransfer(
        ProductViewTransfer $productViewTransfer,
        ProductImageSetStorageTransfer $productImageSetStorageTransfer
    ): ProductViewTransfer {
        foreach ($productImageSetStorageTransfer->getImages() as $productImageStorageTransfer) {
            $productViewTransfer->addImage($productImageStorageTransfer);
        }

        return $productViewTransfer;
    }
}
