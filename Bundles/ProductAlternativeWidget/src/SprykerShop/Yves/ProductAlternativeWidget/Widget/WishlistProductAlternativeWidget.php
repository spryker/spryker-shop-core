<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductAlternativeWidget\Widget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductAlternativeWidget\ProductAlternativeWidgetFactory getFactory()
 */
class WishlistProductAlternativeWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $wishlistName
     */
    public function __construct(ProductViewTransfer $productViewTransfer, string $wishlistName)
    {
        $this
            ->addParameter('item', $productViewTransfer)
            ->addParameter('wishlistName', $wishlistName)
            ->addParameter('products', $this->findAlternativesProducts($productViewTransfer));
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return 'WishlistProductAlternativeWidget';
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductAlternativeWidget/views/wishlist-product-alternative/wishlist-product-alternative.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function findAlternativesProducts(ProductViewTransfer $productViewTransfer): array
    {
        if (!$this->getFactory()->getProductAlternativeStorageClient()->isAlternativeProductApplicable($productViewTransfer)) {
            return [];
        }

        return $this->getFactory()->getProductAlternativeStorageClient()
            ->getConcreteAlternativeProducts($productViewTransfer, $this->getLocale());
    }
}
