<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Reader;

use SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToConfigurableBundleStorageClientInterface;
use SprykerShop\Yves\ConfigurableBundlePage\Expander\ProductConcreteImageExpanderInterface;

class ProductConcreteReader implements ProductConcreteReaderInterface
{
    /**
     * @var \SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToConfigurableBundleStorageClientInterface
     */
    protected $configurableBundleStorageClient;

    /**
     * @var \SprykerShop\Yves\ConfigurableBundlePage\Expander\ProductConcreteImageExpanderInterface
     */
    protected $productConcreteImageExpander;

    /**
     * @param \SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToConfigurableBundleStorageClientInterface $configurableBundleStorageClient
     * @param \SprykerShop\Yves\ConfigurableBundlePage\Expander\ProductConcreteImageExpanderInterface $productConcreteImageExpander
     */
    public function __construct(
        ConfigurableBundlePageToConfigurableBundleStorageClientInterface $configurableBundleStorageClient,
        ProductConcreteImageExpanderInterface $productConcreteImageExpander
    ) {
        $this->configurableBundleStorageClient = $configurableBundleStorageClient;
        $this->productConcreteImageExpander = $productConcreteImageExpander;
    }

    /**
     * @param string[] $skus
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function getProductConcretesBySkusAndLocale(array $skus, string $localeName): array
    {
        $productViewTransfers = $this->configurableBundleStorageClient->getProductConcretesBySkusAndLocale($skus, $localeName);

        return $this->expandProductViewTransfers($productViewTransfers, $localeName);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $productViewTransfers
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function expandProductViewTransfers(array $productViewTransfers, string $localeName): array
    {
        $expandedProductViewTransfers = [];

        foreach ($productViewTransfers as $productViewTransfer) {
            $expandedProductViewTransfers[$productViewTransfer->getSku()] = $this->productConcreteImageExpander->expandProductViewTransferWithImages($productViewTransfer, $localeName);
        }

        return $expandedProductViewTransfers;
    }
}
