<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Expander;

use ArrayObject;
use SprykerShop\Yves\ConfigurableBundlePage\Reader\ProductConcreteReaderInterface;

class ItemExpander implements ItemExpanderInterface
{
    /**
     * @var \SprykerShop\Yves\ConfigurableBundlePage\Reader\ProductConcreteReaderInterface
     */
    protected $productConcreteReader;

    /**
     * @param \SprykerShop\Yves\ConfigurableBundlePage\Reader\ProductConcreteReaderInterface $productConcreteReader
     */
    public function __construct(ProductConcreteReaderInterface $productConcreteReader)
    {
        $this->productConcreteReader = $productConcreteReader;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param string $localeName
     *
     * @return \ArrayObject
     */
    public function expandItemTransfers(ArrayObject $itemTransfers, string $localeName): ArrayObject
    {
        return $this->expandItemTransfersWithName($itemTransfers, $localeName);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param string $localeName
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function expandItemTransfersWithName(ArrayObject $itemTransfers, string $localeName): ArrayObject
    {
        $productViewTransfers = $this->productConcreteReader->getProductConcretesBySkusAndLocale(
            $this->extractSkusFromItemTransfers($itemTransfers),
            $localeName
        );

        foreach ($itemTransfers as $itemTransfer) {
            if (isset($productViewTransfers[$itemTransfer->getSku()])) {
                $itemTransfer->setName($productViewTransfers[$itemTransfer->getSku()]->getName());
            }
        }

        return $itemTransfers;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return string[]
     */
    protected function extractSkusFromItemTransfers(ArrayObject $itemTransfers): array
    {
        $skus = [];

        foreach ($itemTransfers as $itemTransfer) {
            $skus[] = $itemTransfer->getSku();
        }

        return $skus;
    }
}
