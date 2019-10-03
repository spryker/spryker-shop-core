<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReplacementForWidget\Widget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductReplacementForWidget\ProductReplacementForWidgetFactory getFactory()
 * @method \SprykerShop\Yves\ProductReplacementForWidget\ProductReplacementForWidgetConfig getConfig()
 */
class ProductReplacementForListWidget extends AbstractWidget
{
    /**
     * @param string $sku
     */
    public function __construct(string $sku)
    {
        $this->addParameter('products', $this->findReplacementForProducts($sku));

        /** @deprecated Use global widgets instead. */
        $this->addWidgets($this->getFactory()->getProductDetailPageProductReplacementsForWidgetPlugins());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductReplacementForListWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductReplacementForWidget/views/product-replacement-for-list/product-replacement-for-list.twig';
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function findReplacementForProducts(string $sku): array
    {
        $productViewTransferList = [];
        $productReplacementStorageTransfer = $this->getFactory()->getProductAlternativeStorageClient()
            ->findProductReplacementForStorage($sku);
        if (!$productReplacementStorageTransfer) {
            return $productViewTransferList;
        }

        $productViewTransferList = $this->getFactory()
            ->getProductStorageClient()
            ->getProductConcreteViewTransfers($productReplacementStorageTransfer->getProductConcreteIds(), $this->getLocale());

        $filteredProductViewTransferList = [];
        foreach ($productViewTransferList as $productViewTransfer) {
            if ($this->canShowProductReplacementFor($productViewTransfer)) {
                $filteredProductViewTransferList[] = $productViewTransfer;
            }
        }

        return $filteredProductViewTransferList;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    protected function canShowProductReplacementFor(ProductViewTransfer $productViewTransfer): bool
    {
        if (!$this->getConfig()->isProductReplacementFilterActive()) {
            return true;
        }

        return $this->getFactory()->getProductAlternativeStorageClient()
            ->isAlternativeProductApplicable($productViewTransfer);
    }
}
